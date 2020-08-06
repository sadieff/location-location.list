<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use \Bitrix\Main\Service\GeoIp;

// Получим список городов из инфоблока
CModule::IncludeModule("iblock");

$cacheTime = 604800;
$cacheId = 'location';
$cacheDir = '/location/';

$cache = Bitrix\Main\Data\Cache::createInstance();
if ($cache->initCache($cacheTime, $cacheId, $cacheDir))
{
    $arResult = $cache->getVars();
}
elseif ($cache->startDataCache())
{

    $arResult = [];

    $request = CIBlockElement::GetList(
        ['NAME' => 'ASC'],
        ['IBLOCK_ID' => 6],
        false,
        false,
        ['NAME', 'ID', 'IBLOCK_ID', 'PROPERTY_PRICES_LINK']
    );
    while ($element = $request -> GetNextElement()){
        $item = $element->GetFields();
        $arResult['ITEMS'][$item['ID']] = [
            'NAME' => $item['NAME'],
            'PRICE_TYPE' => $item['PROPERTY_PRICES_LINK_VALUE'],
        ];
    }

    $cache->endDataCache($arResult);

}

// определим местоположение пользователя $geoCity и $geoRegion
if(empty($_COOKIE['curcity'])) {
    $geoData = \Classes\Location::getGeoData();
    $arResult['CURRENT_CITY'] = $geoData['CITY'];
}
else $arResult['CURRENT_CITY'] = $_COOKIE['curcity'];

$this->IncludeComponentTemplate();