<?php

namespace Yandex\Geocode;

class Response
{

    protected $_list = [];
    protected $_data;

    public function __construct(array $data)
    {
        $this->_data = $data;
        if (isset($data['response']['GeoObjectCollection']['featureMember'])) {
            foreach ($data['response']['GeoObjectCollection']['featureMember'] as $entry) {
                $this->_list[] = new GeoObject($entry['GeoObject']);
            }
        }
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->_data;
    }

    /**
     * @return mixed
     */
    public function getList()
    {
        return $this->_list;
    }

    /**
     * @return mixed|null
     */
    public function getFirst()
    {
        $result = null;
        if (count($this->_list)) {
            $result = $this->_list[0];
        }
        return $result;
    }

    /**
     * @return mixed|null
     */
    public function getQuery()
    {
        return $this->_data['response']['GeoObjectCollection']['metaDataProperty']['GeocoderResponseMetaData']['request'] ?? null;
    }

    /**
     * @return int|null
     */
    public function getFoundCount()
    {
        $result = null;
        if (isset($this->_data['response']['GeoObjectCollection']['metaDataProperty']['GeocoderResponseMetaData']['found'])) {
            $result = (int)$this->_data['response']['GeoObjectCollection']['metaDataProperty']['GeocoderResponseMetaData']['found'];
        }
        return $result;
    }

    /**
     * @return float|null
     */
    public function getLatitude()
    {
        $result = null;
        if (isset($this->_data['response']['GeoObjectCollection']['metaDataProperty']['GeocoderResponseMetaData']['Point']['pos'])) {
            list(, $latitude) = explode(' ', $this->_data['response']['GeoObjectCollection']['metaDataProperty']['GeocoderResponseMetaData']['Point']['pos']);
            $result = (float)$latitude;
        }
        return $result;
    }

    /**
     * @return float|null
     */
    public function getLongitude()
    {
        $result = null;
        if (isset($this->_data['response']['GeoObjectCollection']['metaDataProperty']['GeocoderResponseMetaData']['Point']['pos'])) {
            list($longitude) = explode(' ', $this->_data['response']['GeoObjectCollection']['metaDataProperty']['GeocoderResponseMetaData']['Point']['pos']);
            $result = (float)$longitude;
        }
        return $result;
    }
}
