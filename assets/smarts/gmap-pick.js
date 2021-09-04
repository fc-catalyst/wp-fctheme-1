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
    if ( lat ) {
        timer = 0;
    }

    setTimeout( function() { // ++replace with interval and max timeout ++ maybe wait if the address is changed
        
        lat = Number( $( '#entity-geo-lat_entity-add' ).val() );
        lng = Number( $( '#entity-geo-long_entity-add' ).val() );
        zoom = Number( $( '#entity-geo-zoom_entity-add' ).val() );
            
        if ( !lat ) { // center of Germany
            lat = 51.1243545;
            lng = 10.18524;
            zoom = 6;
        }

        let coord = {
                lat: Number( lat ),
                lng: Number( lng )
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
            window.setTimeout( function() {
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

    }, timer );

}
