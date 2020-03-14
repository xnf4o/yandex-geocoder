# Laravel Yandex Geocoder

[![Latest Stable Version](https://poser.pugx.org/xnf4o/yandex-geocoder/v/stable)](https://packagist.org/packages/xnf4o/yandex-geocoder) [![Total Downloads](https://poser.pugx.org/xnf4o/yandex-geocoder/downloads)](https://packagist.org/packages/xnf4o/yandex-geocoder) [![License](https://poser.pugx.org/xnf4o/yandex-geocoder/license)](https://packagist.org/packages/xnf4o/yandex-geocoder)

## Installation 

Run composer require command.

```sh
composer require xnf4o/yandex-geocoder
```

### Laravel Setting

After updating composer, register the service provider in bootstrap\app.php
```php
Yandex\Geocode\YandexGeocodeServiceProvider::class
```

Add then alias YaGeo adding its facade to the aliases array in the same file:
```php
'YaGeo' => Yandex\Geocode\Facades\YandexGeocodeFacade::class
```

## Configuration parameters package

Api page: https://tech.yandex.ru/maps/doc/geocoder/desc/concepts/input_params-docpage/

### Copy file config yandex-geocoder.php in config folder
```sh
php artisan vendor:publish
```
Select Tag: yandex-geocoder-config

or
```sh
php artisan vendor:publish --provider="Yandex\Geocode\YandexGeocodeServiceProvider" --tag="yandex-geocoder-config"
```

#### Api key

```php
'api_key' => ''
```

#### Api version

```php
'api_version' => '1.x'
```

#### Language api response

```php
'language' => 'uk_UA'
```

#### Skip object in query

```php
'skip_object' => 0
```

## Methods

1. Get raw data response
    * [getRawData()](https://github.com/xnf4o/yandex-geocoder#getrawdata)
2. Get data response
    * [getData()](https://github.com/xnf4o/yandex-geocoder#getdata)
3. Get name country
    * [getCountry()](https://github.com/xnf4o/yandex-geocoder#getcountry)
4. Get name region
    * [getRegion()](https://github.com/xnf4o/yandex-geocoder#getregion)
5. Get name district
    * [getDistrict()](https://github.com/xnf4o/yandex-geocoder#getdistrict)
6. Get name locality
    * [getLocality()](https://github.com/xnf4o/yandex-geocoder#getlocality)
7. Get name street
    * [getStreet()](https://github.com/xnf4o/yandex-geocoder#getstreet)
8. Get house number
    * [getHouseNumber()](https://github.com/xnf4o/yandex-geocoder#gethousenumber)
9. Get full raw address
    * [getRawFullAddress()](https://github.com/xnf4o/yandex-geocoder#getrawfulladdress)
10. Get full address
    * [getFullAddress()](https://github.com/xnf4o/yandex-geocoder#getfulladdress)
11. Get latitude
    * [getLatitude()](https://github.com/xnf4o/yandex-geocoder#getlatitude)
12. Get longitude
    * [getLongitude()](https://github.com/xnf4o/yandex-geocoder#getlongitude)
13. Get type
    * [getType()](https://github.com/xnf4o/yandex-geocoder#gettype)


# Laravel Яндекс.Геокодирование

Простой пакет laravel Яндекс.Геокодирование

## Установка

Установить пакет с помощью composer

```sh
composer require xnf4o/yandex-geocoder
```

### Laravel настройка пакета

После установки пакета с помощью composer, зарегистрируйте сервис пакета в файле bootstrap/app.php:
```php
Yandex\Geocode\YandexGeocodeServiceProvider::class
```

Затем для быстрого вызов класса пакета, добавьте псевдоним в этот же файле:
```php
'YaGeo' => Yandex\Geocode\Facades\YandexGeocodeFacade::class
```

## Настройка параметров пакета

Документация: https://tech.yandex.ru/maps/doc/geocoder/desc/concepts/input_params-docpage/

### Копируем файл настроек yandex-geocondig.php в config папку
```sh
php artisan vendor:publish
```
Выбираем Tag: yandex-geocoder-config

или
```sh
php artisan vendor:publish --provider="Yandex\Geocode\YandexGeocodeServiceProvider" --tag="yandex-geocoder-config"
```

#### Ключ API

```php
'api_key' => ''
```

#### API версия

```php
'api_version' => '1.x'
```

#### Язык ответа

```php
'language' => 'uk_UA'
```

#### Количество пропускаемых объектов в запросе

```php
'skip_object' => 0
```

## Использование

```php
dd(

    \YaGeo::make()->setQuery('Украина, Киев')->load()

);
```
## Методы

1. Получить сырые данные ответа
    * [getRawData()](https://github.com/xnf4o/yandex-geocoder#getrawdata)
2. Получить данные ответа 
    * [getData()](https://github.com/xnf4o/yandex-geocoder#getdata)
3. Получить имя страны
    * [getCountry()](https://github.com/xnf4o/yandex-geocoder#getcountry)
4. Получить имя области
    * [getRegion()](https://github.com/xnf4o/yandex-geocoder#getregion)
5. Получить имя района
    * [getDistrict()](https://github.com/xnf4o/yandex-geocoder#getdistrict)
6. Получить имя населенного пункта
    * [getLocality()](https://github.com/xnf4o/yandex-geocoder#getlocality)
7. Получить имя улицы
    * [getStreet()](https://github.com/xnf4o/yandex-geocoder#getstreet)
8. Получить номер дома
    * [getHouseNumber()](https://github.com/xnf4o/yandex-geocoder#gethousenumber)
9. Получить полный сырой адрес
    * [getRawFullAddress()](https://github.com/xnf4o/yandex-geocoder#getrawfulladdress)
10. Получить полный адрес
    * [getFullAddress()](https://github.com/xnf4o/yandex-geocoder#getfulladdress)
11. Получить широту
    * [getLatitude()](https://github.com/xnf4o/yandex-geocoder#getlatitude)
12. Получить долготу
    * [getLongitude()](https://github.com/xnf4o/yandex-geocoder#getlongitude)
13. Получить тип
    * [getType()](https://github.com/xnf4o/yandex-geocoder#gettype)

# Methods - Методы:

### getRawData() ###
```php
use YaGeo;

$data = YaGeo::setQuery('Kiev, Vishnevoe, Lesi Ukrainki, 57')->load();

$data = $data->getResponse()->getRawData();
```
### getData() ###
```php
use YaGeo;

$data = YaGeo::setQuery('Kiev, Vishnevoe, Lesi Ukrainki, 57')->load();

$data = $data->getResponse()->getData();
```
### getCountry() ###
```php
use YaGeo;

$data = YaGeo::setQuery('Kiev, Vishnevoe, Lesi Ukrainki, 57')->load();

$data = $data->getResponse()->getCountry();
```
### getRegion() ###
```php
use YaGeo;

$data = YaGeo::setQuery('Kiev, Vishnevoe, Lesi Ukrainki, 57')->load();

$data = $data->getResponse()->getCountry();
```
### getDistrict() ###
```php
use YaGeo;

$data = YaGeo::setQuery('Kiev, Vishnevoe, Lesi Ukrainki, 57')->load();

$data = $data->getResponse()->getDistrict();
```
### getLocality() ###
```php
use YaGeo;

$data = YaGeo::setQuery('Kiev, Vishnevoe, Lesi Ukrainki, 57')->load();

$data = $data->getResponse()->getLocality();
```
### getStreet() ###
```php
use YaGeo;

$data = YaGeo::setQuery('Kiev, Vishnevoe, Lesi Ukrainki, 57')->load();

$data = $data->getResponse()->getStreet();
```
### getHouseNumber() ###
```php
use YaGeo;

$data = YaGeo::setQuery('Kiev, Vishnevoe, Lesi Ukrainki, 57')->load();

$data = $data->getResponse()->getHouseNumber();
```
### getRawFullAddress() ###
```php
use YaGeo;

$data = YaGeo::setQuery('Kiev, Vishnevoe, Lesi Ukrainki, 57')->load();

$data = $data->getResponse()->getRawFullAddress();
```
### getFullAddress() ###
```php
use YaGeo;

$data = YaGeo::setQuery('Kiev, Vishnevoe, Lesi Ukrainki, 57')->load();

$data = $data->getResponse()->getFullAddress();
```
### getLatitude() ###
```php
use YaGeo;

$data = YaGeo::setQuery('Kiev, Vishnevoe, Lesi Ukrainki, 57')->load();

$data = $data->getResponse()->getLatitude();
```
### getLongitude() ###
```php
use YaGeo;

$data = YaGeo::setQuery('Kiev, Vishnevoe, Lesi Ukrainki, 57')->load();

$data = $data->getResponse()->getLongitude();
```
### getType() ###
```php
use YaGeo;

$data = YaGeo::setQuery('Kiev, Vishnevoe, Lesi Ukrainki, 57')->load();

$data = $data->getResponse()->getType();
```
