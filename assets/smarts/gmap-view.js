/*
 * Add Google Maps the place & marker view-only simple map
 */

function fcAddGmapView(selector = '') {

    if ( !selector ) { return }

    let $ = jQuery,
        $self = $( selector );

    if ( !$self.length ) { return }

    let lat = $self.attr( 'data-lat' ),
        lng = $self.attr( 'data-long' );

    if ( !lat || !lng ) { return }
        
    let zoom = $self.attr( 'data-zoom' ),
        addr = $self.attr( 'data-addr' ),
        title = $self.attr( 'data-title' ),
        coord = {
            lat: Number( lat ),
            lng: Number( lng )
        },
        gmap = new google.maps.Map( $self[0], {
            zoom: zoom ? zoom : 17,
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
            title: title,
            icon: {
                url: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 25 36'%3E%3Cpath d='M 12.5,-2e-7 C 5,-2e-7 0,5.5 0,12.4 0,19.9 12.6,36 12.6,36 c 0,0.25 12.4,-16.1 12.4,-23.6 C 25,5.5 19,-2e-7 12.5,-2e-7 Z m 0.1,5.3 a 7,7 0 0 1 7,7 7,7 0 0 1 -7,7 7,7 0 0 1 -7,-7 7,7 0 0 1 7,-7 z' fill='%2323667b' stroke='none'/%3E%3C/svg%3E",
                scaledSize: new google.maps.Size(25, 36)
            },
            animation: google.maps.Animation.DROP
        });

    marker.setMap( gmap );
    
    
    $self.after( '<div>' + addr + '</div>' );

}
