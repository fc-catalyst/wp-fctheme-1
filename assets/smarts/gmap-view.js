/*
 * Add Google Maps the place & marker view-only simple map
 */

function fcAddGmapView(selector = '') {

    if ( !selector ) { return }

    let $ = jQuery,
        $self = $( selector );

    if ( !$self.length ) { return }

    let lat = $self.attr( 'data-lat' ),
        lng = $self.attr( 'data-long' ),
        zoom = $self.attr( 'data-zoom' ),
        addr = $self.attr( 'data-addr' ), // ++ if no anything - filters
        title = $self.attr( 'data-title' ),
        coord = {
            lat: Number( lat ),
            lng: Number( lng )
        },
        gmap = new google.maps.Map( $self[0], {
        zoom: zoom ? zoom : 17,
        center: coord
    }),
        marker = new google.maps.Marker( {
        position: coord,
        gmap,
        title: title,
    });
    marker.setMap( gmap );
    
    
    $self.after( '<div>' + addr + '</div>' );

}
