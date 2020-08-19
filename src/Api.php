<?php

namespace Yandex\Geocode;
use Config;
use Yandex\Geocode\Exception\CurlError;
use Yandex\Geocode\Exception\ServerError;

class Api
{
    protected $_version = '1.x';
    protected $_filters = array();
    protected $_response;
    private $language = 'ru_RU';

    public function __construct()
    {
        $this->clear();
        $this->setLang();
        $this->setToken();
        $this->setOffset();
    }

    /**
     * @return $this
     */
    public function clear(): self
    {
        $this->_filters = array(
            'format' => 'json',
        );
        $this->_response = null;
        return $this;
    }

    /**
     * @param string $language
     * @return $this
     */
    public function setLang($language = ''): self
    {
        if ($language == '') {
            if (config('yandex-geocoder.language')) {
                $this->_filters['lang'] = (string)config('yandex-geocoder.language');
            } else {
                $this->_filters['lang'] = (string)$this->language;
            }
        } else {
            $this->_filters['lang'] = (string)$language;
        }
        return $this;

    }

    /**
     * @return $this
     */
    public function setToken(): self
    {
        if (config('yandex-geocoder.api_key')) {
            $this->_filters['apikey'] = (string)config('yandex-geocoder.api_key');
        }
        return $this;
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function setOffset($offset = 0): self
    {
        if (!$offset) {
            if (config('yandex-geocoder.skip_object')) {
                $this->_filters['skip'] = (int)config('yandex-geocoder.skip_object');
            }
        } else {
            $this->_filters['skip'] = (int)$offset;
        }
        return $this;
    }

    /**
     * @param array $options
     * @return $this
     * @throws CurlError
     * @throws Exception
     * @throws ServerError
     */
    public function load(array $options = []): self
    {

        $apiUrl = sprintf('https://geocode-maps.yandex.ru/%s/?%s', $this->_version, http_build_query($this->_filters));
        $curl = curl_init($apiUrl);
        $options += array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPGET => 1,
            CURLOPT_FOLLOWLOCATION => 1,
        );
        curl_setopt_array($curl, $options);
        $data = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            throw new CurlError($error);
        }
        curl_close($curl);
        if (in_array($code, array(500, 502), true)) {

            $msg = strip_tags($data);

            throw new ServerError(trim($msg), $code);
        }
        $data = json_decode($data, true);
        if (empty($data)) {
            $msg = sprintf('Can\'t load data by url: %s', $apiUrl);
            throw new Exception($msg);
        }
        $this->_response = new Response($data);
        return $this;
    }

    public function getResponse()
    {
        return $this->_response;
    }

    /**
     * @param $longitude
     * @param $latitude
     * @return $this
     */
    public function setPoint($longitude, $latitude): self
    {

        $longitude = (float)$longitude;
        $latitude = (float)$latitude;
        $this->_filters['geocode'] = sprintf('%F,%F', $longitude, $latitude);
        return $this;

    }

    /**
     * @param $lengthLng
     * @param $lengthLat
     * @param null $longitude
     * @param null $latitude
     * @return $this
     */
    public function setArea($lengthLng, $lengthLat, $longitude = null, $latitude = null): self
    {
        $lengthLng = (float)$lengthLng;
        $lengthLat = (float)$lengthLat;
        $this->_filters['spn'] = sprintf('%f,%f', $lengthLng, $lengthLat);

        if (!empty($longitude) && !empty($latitude)) {
            $longitude = (float)$longitude;
            $latitude = (float)$latitude;
            $this->_filters['ll'] = sprintf('%f,%f', $longitude, $latitude);
        }
        return $this;
    }

    /**
     * @param $areaLimit
     * @return $this
     */
    public function useAreaLimit($areaLimit): self
    {
        $this->_filters['rspn'] = $areaLimit ? 1 : 0;
        return $this;

    }

    /**
     * @param $query
     * @return $this
     */
    public function setQuery($query): self
    {
        $this->_filters['geocode'] = (string)$query;
        return $this;
    }

    /**
     * @param $kind
     * @return $this
     */
    public function setKind($kind): self
    {
        $this->_filters['kind'] = (string)$kind;
        return $this;
    }

    /**
     * @param $limit
     * @return $this
     */
    public function setLimit($limit): self
    {
        $this->_filters['results'] = (int)$limit;
        return $this;

    }
}
