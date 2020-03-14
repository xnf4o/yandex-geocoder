<?php

namespace Yandex\Geocode;

use Config;
use Yandex\Geocode\Exception\CurlError;
use Yandex\Geocode\Exception\ServerError;

/**
 * Class Api
 *
 * @package Yandex\Geocode
 *
 * @license The MIT License (MIT)
 *
 * @see http://api.yandex.ru/maps/doc/geocoder/desc/concepts/About.xml
 *
 */
class Api
{
    protected $_version = '1.x';
    protected $_filters = array();
    protected $_response;
    private $api_key = '';
    private $language = 'ru_RU';

    public function __construct()
    {
        $this->clear();
        $this->setLang();
        $this->setToken();
        $this->setOffset();
    }

    /**
     * Clear filters geocoding
     *
     * Очистка фильтров гео-кодирования
     *
     * @return OBJECT
     *
     */
    public function clear()
    {
        $this->_filters = array(
            'format' => 'json',
        );
        $this->_response = null;
        return $this;
    }

    /**
     *
     * Preferred language description objects
     *
     * Предпочитаемый язык описания объектов
     * @param string $language
     * @return OBJECT
     */
    public function setLang($language = '')
    {
        if ($language == '') {
            if (config('yandex-geocoding.language')) {
                $this->_filters['lang'] = (string)config('yandex-geocoding.language');
            } else {
                $this->_filters['lang'] = (string)$this->language;
            }
        } else {
            $this->_filters['lang'] = (string)$language;
        }
        return $this;

    }

    /**
     *
     * Key api Yandex.Maps
     *
     * Ключ API Яндекс.Карт
     * @see http://api.yandex.ru/maps/form.xml
     * @return self
     */
    public function setToken(): self
    {
        if (config('yandex-geocoding.api_key')) {
            $this->_filters['key'] = (string)config('yandex-geocoding.api_key');
        }
        return $this;
    }

    /**
     *
     * Amount objects in response (start with first), which is necessary skip
     *
     * Количество объектов в ответе (начиная с первого), которое необходимо пропустить
     *
     * @param INTEGER $offset
     *
     * @return OBJECT
     *
     */
    public function setOffset($offset = 0)
    {
        if (!$offset) {
            if (config('yandex-geocoding.skip_object')) {
                $this->_filters['skip'] = (int)config('yandex-geocoding.skip_object');
            }
        } else {
            $this->_filters['skip'] = (int)$offset;
        }
        return $this;
    }

    /**
     *
     * Load response
     *
     * Загрузка ответа
     *
     * @param array $options Curl options
     * @return $this
     * @throws Exception
     * @throws Exception\CurlError
     * @throws Exception\ServerError
     */
    public function load(array $options = [])
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

        if (in_array($code, array(500, 502))) {

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

    /**
     *
     * Get response
     *
     * Получение ответа
     *
     * @return GeoObject[]
     *
     */
    public function getResponse()
    {
        return $this->_response->getList();

    }

    /**
     *
     * Geocoding by cordinates
     *
     * Гео-кодирование по координатам
     *
     * @see    http://api.yandex.ru/maps/doc/geocoder/desc/concepts/input_params.xml#geocode-format
     *
     * @param FLOAT $longitude Долгота в градусах
     *
     * @param FLOAT $latitude Широта в градусах
     *
     * @return OBJECT
     *
     */
    public function setPoint($longitude, $latitude)
    {

        $longitude = (float)$longitude;
        $latitude = (float)$latitude;
        $this->_filters['geocode'] = sprintf('%F,%F', $longitude, $latitude);
        return $this;

    }

    /**
     *
     * Geographical region search object
     * Географическая область поиска объекта
     *
     * @param FLOAT $lengthLng Разница между максимальной и минимальной долготой в градусах
     * @param FLOAT $lengthLat Разница между максимальной и минимальной широтой в градусах
     * @param NULL|FLOAT $longitude Долгота в градусах
     * @param NULL|FLOAT $latitude Широта в градусах
     * @return OBJECT
     *
     */
    public function setArea($lengthLng, $lengthLat, $longitude = null, $latitude = null)
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
     *
     * Allow limit search objects by regions, given self::setArea()
     *
     * Позволяет ограничить поиск объектов областью, заданной self::setArea()
     * @param BOOLEAN $areaLimit
     * @return OBJECT
     */

    public function useAreaLimit($areaLimit)
    {
        $this->_filters['rspn'] = $areaLimit ? 1 : 0;
        return $this;

    }

    /**
     *
     * Geocoding by query (address/coordinates)
     *
     * Гео-кодирование по запросу (адрес/координаты)
     * @param STRING $query
     * @return OBJECT
     */
    public function setQuery($query)
    {
        $this->_filters['geocode'] = (string)$query;
        return $this;
    }

    /**
     *
     * View toponyms (only for reverse geocoding)
     *
     * Вид топонима (только для обратного геокодирования)
     * @param STRING $kind
     * @return OBJECT
     */
    public function setKind($kind)
    {
        $this->_filters['kind'] = (string)$kind;
        return $this;
    }

    /**
     *
     * Maximum amount return objects (default 10)
     *
     * Максимальное количество возвращаемых объектов (по-умолчанию 10)
     * @param INTEGER $limit
     * @return OBJECT
     */
    public function setLimit($limit)
    {
        $this->_filters['results'] = (int)$limit;
        return $this;

    }
}
