/*
 * Add Google Maps place picker, must be loaded after gmap-view.php
 */

function fcAddGmapPick(gmap, holder) {

    if ( !gmap ) { return }
    
    if ( !holder && typeof gmap.__gm !== 'undefined' && gmap.__gm.Da !== 'undefined' ) {
        holder = gmap.__gm.Da;
    }

    // add draggable marker
    const marker = new google.maps.Marker( {
            position: gmap.getCenter(),
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

    // trigger the 'map_changed' event
    google.maps.event.addListener( gmap, 'click', function(e) {
        marker.setPosition( e.latLng );
        setTimeout( function() { gmap.panTo( e.latLng ); }, 100 ); // just for smooth animation
        dispatch();
    });
    google.maps.event.addListener( gmap, 'zoom_changed', function() { dispatch(); });
    google.maps.event.addListener( marker, 'dragend', function() { dispatch() });

    function dispatch() {
        holder.dispatchEvent( new CustomEvent( 'map_changed', { detail: {
            'gmap' : gmap,
            'marker' : marker
        }}));
    }
    
    return marker;
}