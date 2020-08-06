<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>

<div class="location-header">
    <div class="location-dsc">
        ВАШ ГОРОД:
    </div>
    <div class="location-current js-location-list">
         <?=$arResult['CURRENT_CITY'];?>
    </div>

    <div class="location-box">
        <div class="location-close js-location-close"></div>

        <div class="location-box_current">
            Ваш город <span><?=$arResult['CURRENT_CITY'];?></span>
        </div>
        <div class="location-box_link js-location-close">
            Да, все верно!
        </div>
        <div class="location-box_link js-location-popup">
            Нет, выбрать другой город
        </div>
        <div class="location-box_info">
            Внимание! От выбранного города зависят сроки доставки
        </div>
    </div>
</div> <!-- location-header -->

<div class="location-popup">
    <div class="location-close-popup"></div>
    <div class="location-popup_header">
        <div class="location-popup_title">
            Выберите свой город
        </div>
        <div class="location-popup_search">
            <input type="text" name="loc_search" placeholder="Введите название города...">
        </div>
    </div>
    <div class="location-popup_list">
        <ul>
            <? foreach ($arResult['ITEMS'] as $arItem): ?>
                <li data-city="<?=$arItem['NAME'];?>"><?=$arItem['NAME'];?></li>
            <? endforeach; ?>
        </ul>
    </div>
</div> <!-- location-popup -->