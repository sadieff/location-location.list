<?
namespace Classes;


class Location
{
    /**
     * Функция возвращает код типа цены для текщего пользователя
     * @return string
     */
    static public function getPriceCode()
    {
        $currentPriceId = self::getPriceId();

        if($currentPriceId > 0) {

            $dbPriceType = \CCatalogGroup::GetList(
                ["SORT" => "ASC"],
                ["ID" => $currentPriceId]
            );
            $arPriceType = $dbPriceType->Fetch();

        }
        else {
            $location = self::getGeoData();
            $arPriceType['NAME'] = self::getFO($location['REGION']);
        }

        return $arPriceType['NAME'];
    }

    /**
     * Функция возвращает готовый массив для параметра PRICE_CODE.
     * @return array
     */
    static public function getArrPrices()
    {

        $return = [];
        global $USER;
        if ($USER->IsAdmin()) {
            $currentCode = self::getPriceCode();

            if($currentCode == 'BASE') $return[] = 'BASE';
            else $return = [
                $currentCode,
                'BASE'
            ];
        }
        else {
            $return[] = 'BASE';
        }

        return $return;
    }

    /**
     * Функция возвращает ID типа цены для текущего пользователя
     * @return int
     */
    static public function getPriceId()
    {
        $cityList = self::getCityList();
        $currentCity = (!empty($_COOKIE['curcity'])) ? $_COOKIE['curcity'] : 0;
        return $cityList[$currentCity];
    }

    /**
     * Функция возвращает местоположение пользователя
     * @return array
     */
    static public function getGeoData()
    {
        $ipAddress = \Bitrix\Main\Service\GeoIp\Manager::getRealIp();
        \Bitrix\Main\Service\GeoIp\Manager::useCookieToStoreInfo(true);
        if($geoLocation = \Bitrix\Main\Service\GeoIp\Manager::getDataResult($ipAddress, "ru", ['cityName','regionName'])) {
            $geoData = $geoLocation->getGeoData();
            $geoCity = $geoData->cityName;
            $geoRegion = $geoData->regionName;
        }
        return [
            'CITY' => $geoCity,
            'REGION' => $geoRegion
        ];
    }

    /**
     * Возвращает массив со списком городов, где город - ключ,
     * а значение - ID типа цены
     * @return array
     */
    static public function getCityList() {

        $cacheTime = 604800;
        $cacheId = 'city_list';
        $cacheDir = '/city_list/';

        \Bitrix\Main\Loader::includeModule("iblock");
        $cache = \Bitrix\Main\Data\Cache::createInstance();
        if ($cache->initCache($cacheTime, $cacheId, $cacheDir))
        {
            $result = $cache->getVars();
        }
        elseif ($cache->startDataCache())
        {

            $result = [];

            $request = \CIBlockElement::GetList(
                ['SORT' => 'ASC'],
                ['IBLOCK_ID' => 6],
                false,
                false,
                ['NAME', 'ID', 'IBLOCK_ID', 'PROPERTY_PRICES_LINK']
            );
            while ($element = $request -> GetNextElement()){
                $item = $element->GetFields();
                $result[$item['NAME']] = $item['PROPERTY_PRICES_LINK_VALUE'];
            }

            $cache->endDataCache($result);

        }

        return $result;

    }

    /**
     * Функция для определения федерального округа
     * @return array
     */
    static public function getFO($region){
        $arMapFO = [
            'CFO' => 'BASE',
            'DFO' => 'DFO',
            'PFO' => 'PFO',
            'SZFO' => 'СЗФО',
            'SKFO' => 'UFO',
            'SFO' => 'SFO',
            'YFO' => 'YFO',
            'UFO' => 'UFO',
        ];
        $arMapRegions = [
            'Амурская область' => 'DFO',
            'Еврейская автономная область' => 'DFO',
            'Камчатский край' => 'DFO',
            'Магаданская область' => 'DFO',
            'Приморский край' => 'DFO',
            'Республика Саха (Якутия)' => 'DFO',
            'Сахалинская область' => 'DFO',
            'Хабаровский край' => 'DFO',
            'Чукотский автономный округ' => 'DFO',

            'Кировская область' => 'PFO',
            'Нижегородская область' => 'PFO',
            'Оренбургская область' => 'PFO',
            'Пензенская область' => 'PFO',
            'Пермский край' => 'PFO',
            'Республика Башкортостан' => 'PFO',
            'Республика Марий Эл' => 'PFO',
            'Республика Мордовия' => 'PFO',
            'Республика Татарстан' => 'PFO',
            'Самарская область' => 'PFO',
            'Саратовская область' => 'PFO',
            'Удмуртская Республика' => 'PFO',
            'Ульяновская область' => 'PFO',
            'Чувашская Республика' => 'PFO',

            'Кабардино-Балкарская Республика' => 'SKFO',
            'Карачаево-Черкесская Республика' => 'SKFO',
            'Республика Дагестан' => 'SKFO',
            'Республика Ингушетия' => 'SKFO',
            'Республика Северная Осетия-Алания' => 'SKFO',
            'Ставропольский край' => 'SKFO',
            'Чеченская Республика' => 'SKFO',

            'Архангельская область' => 'SZFO',
            'Вологодская область' => 'SZFO',
            'Калининградская область' => 'SZFO',
            'Ленинградская область' => 'SZFO',
            'Мурманская область' => 'SZFO',
            'Ненецкий автономный округ' => 'SZFO',
            'Новгородская область' => 'SZFO',
            'Псковская область' => 'SZFO',
            'Республика Карелия' => 'SZFO',
            'Республика Коми' => 'SZFO',

            'Алтайский край' => 'SFO',
            'Забайкальский край' => 'SFO',
            'Иркутская область' => 'SFO',
            'Кемеровская область' => 'SFO',
            'Красноярский край' => 'SFO',
            'Новосибирская область' => 'SFO',
            'Омская область' => 'SFO',
            'Республика Алтай' => 'SFO',
            'Республика Бурятия' => 'SFO',
            'Республика Тыва' => 'SFO',
            'Республика Хакасия' => 'SFO',
            'Томская область' => 'SFO',

            'Курганская область' => 'YFO',
            'Свердловская область' => 'YFO',
            'Тюменская область' => 'YFO',
            'Ханты-Мансийский автономный округ' => 'YFO',
            'Челябинская область' => 'YFO',
            'Ямало-Ненецкий автономный округ' => 'YFO',

            'Белгородская область' => 'CFO',
            'Брянская область' => 'CFO',
            'Владимирская область' => 'CFO',
            'Воронежская область' => 'CFO',
            'Ивановская область' => 'CFO',
            'Калужская область' => 'CFO',
            'Костромская область' => 'CFO',
            'Курская область' => 'CFO',
            'Липецкая область' => 'CFO',
            'Московская область' => 'CFO',
            'Орловская область' => 'CFO',
            'Рязанская область' => 'CFO',
            'Смоленская область' => 'CFO',
            'Тамбовская область' => 'CFO',
            'Тверская область' => 'CFO',
            'Тульская область' => 'CFO',
            'Ярославская область' => 'CFO',

            'Астраханская область' => 'UFO',
            'Волгоградская область' => 'UFO',
            'Краснодарский край' => 'UFO',
            'Республика Адыгея' => 'UFO',
            'Республика Калмыкия' => 'UFO',
            'Ростовская область' => 'UFO',
        ];

        return $arMapFO[$arMapRegions[$region]];
    }
}