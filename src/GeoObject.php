<?php

namespace Yandex\Geocode;

class GeoObject
{
    protected $_addressHierarchy = [
        'Country' => array('AdministrativeArea'),
        'AdministrativeArea' => array('SubAdministrativeArea', 'Locality'),
        'SubAdministrativeArea' => array('Locality'),
        'Locality' => array('DependentLocality', 'Thoroughfare'),
        'DependentLocality' => array('DependentLocality', 'Thoroughfare'),
        'Thoroughfare' => array('Premise'),
        'Premise' => array(),
    ];
    protected $_data;
    protected $_rawData;

    public function __construct(array $rawData)
    {
        $data = array(
            'Address' => $rawData['metaDataProperty']['GeocoderMetaData']['text'],
            'Kind' => $rawData['metaDataProperty']['GeocoderMetaData']['kind'],
        );
        array_walk_recursive(
            $rawData,
            static function ($value, $key) use (&$data) {
                if (in_array(
                    $key,
                    array(
                        'CountryName',
                        'CountryNameCode',
                        'AdministrativeAreaName',
                        'SubAdministrativeAreaName',
                        'LocalityName',
                        'DependentLocalityName',
                        'ThoroughfareName',
                        'PremiseNumber',
                    )
                )) {
                    $data[$key] = $value;
                }
            }
        );

        if (isset($rawData['Point']['pos'])) {
            $pos = explode(' ', $rawData['Point']['pos']);
            $data['Longitude'] = (float)$pos[0];
            $data['Latitude'] = (float)$pos[1];
        }

        $this->_data = $data;
        $this->_rawData = $rawData;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return array('_data');
    }

    /**
     * @return array
     */
    public function getRawData(): array
    {

        return $this->_rawData;

    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->_data;
    }

    /**
     * @return mixed|null
     */
    public function getLatitude()
    {
        return $this->_data['Latitude'] ?? null;
    }

    /**
     * @return mixed|null
     */
    public function getLongitude()
    {

        return $this->_data['Longitude'] ?? null;

    }

    /**
     * @return mixed|null
     */
    public function getFullAddress()
    {
        return $this->_data['Address'] ?? null;
    }

    /**
     * @return mixed|null
     */
    public function getType()
    {
        return $this->_data['Kind'] ?? null;
    }

    /**
     * @return mixed|null
     */
    public function getCountry()
    {

        return $this->_data['CountryName'] ?? null;

    }

    /**
     * @return mixed|null
     */
    public function getCountryCode()
    {
        return $this->_data['CountryNameCode'] ?? null;
    }

    /**
     * @return mixed|null
     */
    public function getRegion()
    {
        return $this->_data['AdministrativeAreaName'] ?? null;
    }

    /**
     * @return mixed|null
     */
    public function getDistrict()
    {
        return $this->_data['SubAdministrativeAreaName'] ?? null;
    }

    /**
     * @return mixed|null
     */
    public function getLocality()
    {
        return $this->_data['LocalityName'] ?? null;
    }

    /**
     * @return mixed|null
     */
    public function getDependentLocalityName()
    {
        return $this->_data['DependentLocalityName'] ?? null;
    }

    /**
     * @return mixed|null
     */
    public function getStreet()
    {

        return $this->_data['ThoroughfareName'] ?? null;

    }

    /**
     * @return mixed|null
     */
    public function getHouseNumber()
    {

        return $this->_data['PremiseNumber'] ?? null;

    }

    /**
     * @return array
     */
    public function getRawFullAddress(): array
    {
        return array_unique(
            (array)$this->_parseLevel(
                $this->_rawData['metaDataProperty']['GeocoderMetaData']['AddressDetails']['Country'],
                'Country'
            )
        );
    }

    /**
     * @param array $level
     * @param $levelName
     * @param array $address
     * @return array|void
     */
    protected function _parseLevel(array $level, $levelName, &$address = [])
    {
        if (!isset($this->_addressHierarchy[$levelName])) {
            return;
        }

        $nameProp = $levelName === 'Premise' ? 'PremiseNumber' : $levelName . 'Name';

        if (isset($level[$nameProp])) {

            $address[] = $level[$nameProp];

        }

        foreach ($this->_addressHierarchy[$levelName] as $child) {

            if (!isset($level[$child])) {

                continue;

            }

            $this->_parseLevel($level[$child], $child, $address);

        }

        return $address;

    }
}
