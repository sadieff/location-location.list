**Компонент для вывода цен в соответствии с выбранным городом.** 

Вызов компонента для выбора города:

```
$APPLICATION->IncludeComponent("location:location.list",
    ".default",
    [],
    false
);
```

Для вывода цен необходимо объявить класс в init.php:

```
CModule::AddAutoloadClasses('', [
    '\Classes\Location' => '/local/components/location/location.list/class.php'
]);
```

И передаем в параметр PRICE_CODE

`"PRICE_CODE" => \Classes\Location::getArrPrices(),` 