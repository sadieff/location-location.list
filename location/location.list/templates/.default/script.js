$(document).ready(function(){

    var location = new Location({
        closeButton: '.js-location-list,.js-location-close',
        closePopupButton: '.location-close-popup',
        locationBox: '.location-box',
        locationPopup: '.location-popup',
        locationHeaderClass: '.location-header',
        searchInput: '.location-popup_search input',
        locationElements: '.location-popup_list li',
        buttonOpenList: '.js-location-popup',
        cityElement: '.location-popup_list li'
    });

});

class Location
{
    constructor(params)
    {
        this.opt = params;
        var self = this;

        // Закрытие\открытие блока при клике на кнопку закрытия
        $(params.closeButton).on('click', function(){
            $(params.locationBox).toggleClass('active');

            if(!localStorage.locationpopup) {
                localStorage.locationpopup = 'closed';
            }
        });

        // Закрытие блока при клике вне его
        $(document).on('click', function(e){
            if (!$(e.target).closest(params.locationHeaderClass).length) {
                $(params.locationBox).removeClass('active');

                if(!localStorage.locationpopup) {
                    localStorage.locationpopup = 'closed';
                }
            }
        });
        // Закрытие блока при клике вне его
        $(document).on('click', function(e){
            if (!$(e.target).closest(params.locationHeaderClass).length) {
                $(params.locationBox).removeClass('active');
            }
        });

        // Поиск города
        $(params.searchInput).on('input', function () {
            self.citySearch($(this).val());
        });

        // Открытие блока со списком городов
        $(params.buttonOpenList).on('click', function(){
            $(params.locationPopup).addClass('active');
        });

        // Закрытие блока со списком городов
        $(params.closePopupButton).on('click', function(){
            $(params.locationPopup).removeClass('active');
        });

        // Закрытие  блока со списком городов при клике вне его
        $(document).on('click', function(e){
            if (!$(e.target).closest(params.locationPopup).length && !$(e.target).closest(params.locationHeaderClass).length) {
                $(params.locationPopup).removeClass('active');
            }
        });

        //Выбор города. Запишем в куки и обновим страницу
        $(params.cityElement).on('click', function(){
            let date = new Date(Date.now() + 86400e3);
            let city = $(this).data('city');
            date = date.toUTCString();
            document.cookie = "curcity="+city+"; path=/; expires=" + date;

            location.reload();
        });

        // при первом посещении сайта откроем плашку с выбором города
        if(!localStorage.locationpopup) {
            $(params.locationBox).addClass('active');
        }

    }

    /**
     * Функция для поиска городов в списке
     * @param search - содержимое строки поиска
     */
    citySearch(search){
        search = search.toLowerCase();
        if (search.length > 0) {
            $(this.opt.locationElements).each(function () {
                console.log();
                if ($(this).text().toLowerCase().indexOf(search) < 0) {
                    $(this).css('display', 'none');
                }
                else $(this).css('display', 'block');
            });
        }
        else {
            $(this.opt.locationElements).each(function () {
                $(this).css('display', 'block');
            });
        }
    }
}