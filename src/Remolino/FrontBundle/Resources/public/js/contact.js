var browser_mobile = (/android|webos|iphone|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
var browser_touch = (/android|webos|iphone|ipod|ipad|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
var mapPointsCenter;
var markers = [];
var mapLocation;
var markerPath;
var bounds;

$(document).ready(function () {
    markerPath = $('#markerpath').val();
    mapPointsCenter = $('#mapCenterPoint').val();
    mapCenterPoint = mapPointsCenter.split(',');
    var latitud = parseFloat(mapCenterPoint[0]);
    var longitud = parseFloat(mapCenterPoint[1]);

    // Map
    createMap('gmap', latitud, longitud);
    $.each(mapPoints, function (index, value) {
        if (jQuery.type(value) !== "undefined") {
            addPOIS(index);
        }
    });

    // Form
    $('#contact-form').validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            message: {
                required: true
            }
        },
        errorClass: "error",
        errorPlacement: function (error, element) {
            /*$(element).prev().children().text(error[0].innerText);*/
        },
        success: function (label, element) {
            /*$(element).prev().children().text('');*/
        },
        submitHandler: function (form) {
            var email = $('#contact-form #email').val();
            var message = $('#contact-form #message').val();
            //var lang = $('#lang').val();
            //var ruta = '/'+lang+'/contact/email';
            var ruta = '/contact/email';
            // if(lang == 'en'){
            //     ruta = '/contact/email';
            // }
            // Change State BtnEnviar
            $('button').text('Sending...');
            $('button').attr('disabled', true);
            // Data
            var data = '&email=' + email + '&message=' + message;

            $.ajax({
                type: "POST",
                url: ruta,
                data: data,
                dataType: 'json',
                success: function (ret) {
                    // Message was sent
                    if (ret.status == "OK") {
                        $('#contact-form')[0].reset();
                        $('#contact-form').hide();
                        $('.form-response').parent().addClass("form-response-h");
                        $('.form-response').html(ret.msg);
                    } else {
                        console.log('error');
                    }
                    $('#resp-ajax').html(ret);
                    $('button').text('SUBMIT');
                    $('button').attr('disabled', false);
                }
            });
            return false;
        }
    });
});

function createMap(mapId, latitud, longitud) {
    var desarrolloLatlng = new google.maps.LatLng(latitud, longitud);
    bounds = new google.maps.LatLngBounds();
    bounds.extend(desarrolloLatlng);
    var mymap = document.getElementById(mapId),
        map_options = {
            zoom: 2,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: false,
            center: (browser_mobile) ? desarrolloLatlng : new google.maps.LatLng(latitud, longitud),
            mapTypeControl: false,
            streetViewControl: false,
            zoomControl: true,
            fullscreenControl: false,
            styles: [
                {
                    "featureType": "all",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "saturation": 36
                        },
                        {
                            "color": "#000000"
                        },
                        {
                            "lightness": 40
                        }
                    ]
                },
                {
                    "featureType": "all",
                    "elementType": "labels.text.stroke",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "color": "#000000"
                        },
                        {
                            "lightness": 16
                        }
                    ]
                },
                {
                    "featureType": "all",
                    "elementType": "labels.icon",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#000000"
                        },
                        {
                            "lightness": 20
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#000000"
                        },
                        {
                            "lightness": 17
                        },
                        {
                            "weight": 1.2
                        }
                    ]
                },
                {
                    "featureType": "landscape",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#000000"
                        },
                        {
                            "lightness": 20
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#000000"
                        },
                        {
                            "lightness": 21
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#000000"
                        },
                        {
                            "lightness": 17
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#000000"
                        },
                        {
                            "lightness": 29
                        },
                        {
                            "weight": 0.2
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#000000"
                        },
                        {
                            "lightness": 18
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#000000"
                        },
                        {
                            "lightness": 16
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#000000"
                        },
                        {
                            "lightness": 19
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#000000"
                        },
                        {
                            "lightness": 17
                        }
                    ]
                }
            ]
        };

    map = new google.maps.Map(mymap, map_options);
}

function addPOIS(point) {
    // deleteMarkers();
    var marker = null;
    var infowindow = new google.maps.InfoWindow({
        pixelOffset: new google.maps.Size(-12, 0)
    });

    if (mapPoints[point].length > 0) {
        $.each(mapPoints[point], function (key, data) {
            mapLocation = data.geocode;
            mapLocation = mapLocation.split(',');
            var coordenada_x = parseFloat(mapLocation[0]);
            var coordenada_y = parseFloat(mapLocation[1]);
            var latLng = new google.maps.LatLng(coordenada_x, coordenada_y);
            var html = '<span class="name">' + data.name + '</span>' +
                '<span class="address">' + data.address + '</span>';
            var marker = new google.maps.Marker({
                position: latLng,
                map: map,
                icon: {
                    url: markerPath,
                    size: new google.maps.Size(32, 32),
                    scaledSize: new google.maps.Size(24, 24),
                    anchor: new google.maps.Point(16, 16)
                },
                title: data.name,
                info: html
            });

            markers.push(marker);

            google.maps.event.addListener(marker, 'click', function () {
                infowindow = new google.maps.InfoWindow();
                infowindow.setContent(this.info);
                infowindow.open(map, this);
            });
        });
    }
}