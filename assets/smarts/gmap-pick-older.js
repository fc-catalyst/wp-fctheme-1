/*
 * Add Google Maps place picker
 */

function fcAddGmapPick(selector = '') {

    if ( !selector ) { return }

    let $ = jQuery,
        $self = $( selector );

    if ( !$self.length ) { return }
    
    let lat = 0, lng = 0, zoom = 0, timer = 2000;


    lat = Number( $( '#entity-geo-lat_entity-add' ).val() );
    lng = Number( $( '#entity-geo-long_entity-add' ).val() );
    zoom = Number( $( '#entity-geo-zoom_entity-add' ).val() );

    if ( !lat ) { // center of Germany
        lat = 51.1243545;
        lng = 10.18524;
        zoom = 6;
    }

    let coord = {
            lat: lat,
            lng: lng
        },
        gmap = new google.maps.Map( $self[0], {
            zoom: zoom,
            center: coord,
            //https://mapstyle.withgoogle.com/
            styles: [
                {
                    "featureType": "landscape.man_made",
                    "elementType": "geometry.fill",
                    "stylers": [
                    {
                        "saturation": -100
                    }
                    ]
                },
                {
                    "featureType": "landscape.man_made",
                    "elementType": "geometry.stroke",
                    "stylers": [
                    {
                        "saturation": -100
                    }
                    ]
                },
                {
                    "featureType": "landscape.natural",
                    "elementType": "geometry.fill",
                    "stylers": [
                    {
                        "saturation": -40
                    }
                    ]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "geometry.fill",
                    "stylers": [
                    {
                        "saturation": -40
                    }
                    ]
                },
                {
                    "featureType": "poi",
                    "stylers": [
                    {
                        "visibility": "off"
                    }
                    ]
                },
                {
                    "featureType": "poi.park",
                    "stylers": [
                    {
                        "visibility": "on"
                    }
                    ]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "labels.icon",
                    "stylers": [
                    {
                        "visibility": "off"
                    }
                    ]
                },
                {
                    "featureType": "transit.station",
                    "elementType": "labels.icon",
                    "stylers": [
                    {
                        "color": "#23667b"
                    }
                    ]
                },
                {
                    "featureType": "transit.station",
                    "elementType": "labels.text.fill",
                    "stylers": [
                    {
                        "color": "#23667b"
                    }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "labels.text",
                    "stylers": [
                    {
                        "color": "#23667b"
                    }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "geometry.fill",
                    "stylers": [
                    {
                        "color": "#9fc8d5"
                    }
                    ]
                }
            ]
        }),
        marker = new google.maps.Marker( {
            position: coord,
            gmap,
            icon: {
                url: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 25 36'%3E%3Cpath d='M 12.5,-2e-7 C 5,-2e-7 0,5.5 0,12.4 0,19.9 12.6,36 12.6,36 c 0,0.25 12.4,-16.1 12.4,-23.6 C 25,5.5 19,-2e-7 12.5,-2e-7 Z m 0.1,5.3 a 7,7 0 0 1 7,7 7,7 0 0 1 -7,7 7,7 0 0 1 -7,-7 7,7 0 0 1 7,-7 z' fill='%2323667b' stroke='none'/%3E%3C/svg%3E",
                scaledSize: new google.maps.Size(25, 36)
            },
            title: 'Drag to better specify the location',
            draggable: true,
            animation: google.maps.Animation.DROP
        });
        
    marker.setMap( gmap );
    // +++!!! make the onchange event, so that the address in the box changes too
    google.maps.event.addListener( marker, 'dragend', function() {
        let lat = marker.getPosition().lat(),
            lng = marker.getPosition().lng();
            
        $( '#entity-geo-lat_entity-add' ).val( lat );
        $( '#entity-geo-long_entity-add' ).val( lng );
        
        // ++ if no address initially - fill in the address too:
        // entity-address, entity-region, entity-geo-city, entity-geo-postcode
    });
    
    google.maps.event.addListener( gmap, 'click', function(e) {
        marker.setPosition( e.latLng );
        setTimeout( function() {
            gmap.panTo( marker.getPosition() );
        });
        
        let lat = marker.getPosition().lat(),
            lng = marker.getPosition().lng();
            
        $( '#entity-geo-lat_entity-add' ).val( lat );
        $( '#entity-geo-long_entity-add' ).val( lng );
    });
    
    google.maps.event.addListener( gmap, 'zoom_changed', function() {
        let zoom = gmap.getZoom();
        $( '#entity-geo-zoom_entity-add' ).val( zoom );
    });

/*    // gmap helper field
    const $address = $( '<input type="text" name="gmap_pick_address" id="gmap_pick_address" />' );
    $self.before( $address );
    const autocomplete = new google.maps.places.Autocomplete(
        $address,
        {
            componentRestrictions: { country: ['de'] },
            fields: ['geometry'],
            types: ['address']
        }
    );

    autocomplete.addListener( 'place_changed', function() {
        
        console.log( autocomplete.getPlace() );
    });
//*/
    // track if the lat value is changed due to slow autocomplete
    let correct = setInterval( function() {
        let lat_new = $( '#entity-geo-lat_entity-add' ).val();
        if ( !lat_new || Number( lat_new ) == lat ) { return }

        clearInterval( correct );
        correct = null;

        lat = Number( $( '#entity-geo-lat_entity-add' ).val() );
        lng = Number( $( '#entity-geo-long_entity-add' ).val() );
        zoom = Number( $( '#entity-geo-zoom_entity-add' ).val() );
        coord = {
            lat: lat,
            lng: lng
        };

        setTimeout( function() {
            gmap.panTo( coord );
            gmap.setZoom( zoom );
            marker.setPosition( coord );
        });
    }, 200 );
    setTimeout( function() {
        clearInterval( correct );
        correct = null;
    }, 3000 ); // ++can make autocompleteFilled global to track if autocomplete is still thinking

}
