$(function(){
    initMap();

    var $department = $('#observation_department');
    var $city = $('#observation_city');
    var longitude = parseFloat($('#observation_longitude').val());
    var latitude = parseFloat($('#observation_latitude').val());

    var alertNoCitySelected = false;

    /**
     * cherche les coordonnées via le lieu dit
     */
    $('#observation_locality').on('keyup', function(){
        var cityId = parseInt($city.find('option:selected').val());

        if(isNaN(cityId)){
            if(false === alertNoCitySelected){
                bootbox.alert(messageSelectCityBefore, function(){ alertNoCitySelected = false; });
                alertNoCitySelected = true;
            }
            return false;
        }
        var departmentName =  $department.find('option:selected').text();
        var cityName = $city.find('option:selected').text();

        var address = $(this).val() + ', ' + cityName +' ' + departmentName;

        Search(address);
    });

    /**
     * cherche les coordonnées via la ville sélectionnée
     */
    $city.on('change', function(){
        var departmentName =  $department.find('option:selected').text();
        var cityName = $(this).find('option:selected').text();

        if('' == $(this).val()){
            return;
        }

        var address =  cityName +' ' + departmentName;

        Search(address);
    });

    //on initialise un point dans la carte s'il est déjà renseigné
    if(!isNaN(longitude) && !isNaN(latitude)){
        setCoordinates(longitude, latitude);
    }
});

var map;
var markerPrevious = null;

function initMap() {
    // Create a map object and specify the DOM element for display.
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: defaultLatitude, lng: defaultLongitude },
        scrollwheel: defaultScrollwheel,
        zoom: defaultZoom
    });

    google.maps.event.addListener(map, 'click', function(event) {
        setCoordinates(event.latLng.lng(), event.latLng.lat());
        setAddressByCoordinates(event.latLng.lng(), event.latLng.lat());
    });

}

var coordinatesNotFound = false;
function getGeolocalisationByAddress(address) {
    messagelocalityCoordinatesNotFoundAjax = messagelocalityCoordinatesNotFound;
    $.ajax({
        method: 'GET',
        url: 'http://maps.google.com/maps/api/geocode/json',
        data: {'address': address, 'sensor': false},
        datatype: 'json',
        success: function (data) {
            if (!(data.results.length > 0)) {
                if(false === coordinatesNotFound){
                    bootbox.alert(messagelocalityCoordinatesNotFoundAjax, function(){ coordinatesNotFound = false; });
                    coordinatesNotFound = true;
                }
                return false;
            }

            var coodinates = data.results[0].geometry.location;
            setCoordinates(coodinates.lng, coodinates.lat);
        }
    });
}

var timer;
function Search(test) {
    clearTimeout(timer) // clear the request from the previous event
    if(test.length > 5){
        timer = setTimeout(function() {
            getGeolocalisationByAddress(test);
        }, 500);
    }
}


function setAddressByCoordinates(longitude, latitude){
    var latlng = {lat: parseFloat(latitude), lng: parseFloat(longitude)};
    var geocoder = new google.maps.Geocoder;
    geocoder.geocode({'location': latlng}, function(results, status) {
        //Erreur du service
        if (status !== google.maps.GeocoderStatus.OK) {
            bootbox.alert("Error, the service status is " + status);
            return false;
        }
        //si pas de retour
        if (!results[0]) {
            bootbox.alert(messageLocalityNotFound);
            return false;
        }
        //On récupère la 1ere et la 2me informations du composant adresse pour avoir la première partie de l'adresse et la deuxième
        //Qui donnera une indication accès précise du lieu
        var localityName = results[0].address_components[0].long_name + ' ' + results[0].address_components[1].long_name ;
        setLocalityName(localityName);

        var cityName = results[0].address_components[2].long_name;
        setCity(cityName);
    });

}

function setCoordinates(longitude, latitude){
    if(null !== markerPrevious){
        markerPrevious.setMap(null);
    }

    marker = new google.maps.Marker({
        position: {lat: latitude, lng: longitude},
        map: map,
        title: ""
    });
    map.panTo({lat: latitude, lng: longitude});
    map.setZoom(15);
    markerPrevious = marker;

    setObservationCoordinates(longitude, latitude);
}

function setObservationCoordinates(longitude, latitude)
{
    $('#observation_longitude').val(longitude);
    $('#observation_latitude').val(latitude);
    $('#display_coordinates').text("lgt : " + longitude.toString().substring(0,6) +", lat : "+ latitude.toString().substring(0,6));
}

function setLocalityName(localityName){
    $('#observation_locality').val(localityName);
}

function setCity(cityName){
    $.ajax({
        method:'GET',
        url: urlJsonGetCityByName,
        data: {'name': cityName},
        dataType: 'json',
        success: function(data){
            var department = data.department_id;
            var city = data.id;
            $('#observation_department').val(department).change();

            $('#observation_city').on('change', function(){
                $(this).val(city);
            });
        }
    });
}