!function(){let a=setInterval(function(){let b=document.readyState;if(b!=='complete'&&b!=='interactive'||typeof jQuery==='undefined'){return}let $=jQuery;clearInterval(a);a=null;

        anchor_links();
        menu_events();

        var scrolled = $( window ).scrollTop();
        body_add_scrolled();

        $( window ).on( 'scroll', function() {
            scrolled = $( window ).scrollTop();
            body_add_scrolled();
        });

        /* the functions for the events above */
        function anchor_links() {
            var $anchors = $( 'a[href^="#"]:not([href="#"])' );
            if ( !$anchors.length ) {
                return;
            }
            setTimeout( function() {
                $( 'head' ).append( '<style>html{scroll-behavior:auto!important;}</style>' );
            });
            $anchors.click( function(e) {
                var anchor = $( this ).attr( 'href' ),
                    $target = $( anchor );
                if ( $target.length ) {
                    e.preventDefault();
                }
                scroll_to_object( $target );
                history.pushState( null, null, anchor );
            });
        }

        function body_add_scrolled() {
            var $body = $( 'body' );
            if ( scrolled > 20 ) {
                if ( $body.hasClass( 'scrolled' ) )
                    return;
                $body.addClass( 'scrolled' );
                return;
            }
            $body.removeClass( 'scrolled' );
        }
        
        function scroll_to_object(target) {

            if ( typeof target ==='string' || typeof target === 'object' && !target instanceof $ ) {
                var $target = $( target );
            } else {
                var $target = target;
            }

            if ( !$target || !$target.length ) {
                return;
            }

            var scroll_to = $target.position()['top'] - scroll_offset();
            $( 'html, body' ).animate( {
                scrollTop: scroll_to
            }, 400 );
        }
        
        function scroll_offset() {
            var offset = 0,
                $heightObject = $( '.header-fixed .site-header' );
            if ( $heightObject.length ) {
                offset = $heightObject.height();
            }
            return offset;
        }
        
        function menu_events() {
            var $checkbox = $( '#nav-primary-toggle' ),
                hamburger = $checkbox.next();

            $checkbox.click( function() {
                setTimeout( function() {
                    if ( $checkbox.prop( 'checked' ) ) {
                        document.addEventListener( 'click', menuHide );
                    }
                });
            });

            function menuHide(e) {
                if ( e.target === hamburger[0] ) {
                    e.preventDefault();
                }
                $checkbox.prop( 'checked', false );
                document.removeEventListener( 'click', menuHide, false );
            }
        }
        
        /* hidden fields show-hide */
        $( '.fct-open-next > a' ).click( function(e) {
            e.preventDefault();
            $( this ).parent().toggleClass( 'fct-active' );
        });
        
        
        /* add vertical gallery by class */
        if ( $( '#entity-gallery' ).length ) {
            fcLoadScriptVariable(
                '/wp-content/themes/fct1/assets/smarts/gallery-vertical.js?' + + new Date(),
                'fcAddGallery',
                function() { fcAddGallery( '#entity-gallery' ) }
            );
        }
        
        /* add map by class */
        const gmap_holder = $( '.fct-gmap-view' );
        if ( gmap_holder.length ) {
            fcLoadScriptVariable(
                'https://maps.googleapis.com/maps/api/js?key='+fcGmapKey+'&libraries=places',
                'google'
            );
            fcLoadScriptVariable(
                '/wp-content/themes/fct1/assets/smarts/gmap-view.js?' + + new Date(),
                'fcAddGmapView',
                function() {
                    const gmap = fcAddGmapView( gmap_holder ); // ++move the following to gmap-pick.js and include after view (single marker mode, or something)

                    if ( !gmap ) { return }
                    
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
                        setTimeout( function() { gmap.panTo( e.latLng ); }, 100 ); // just to smooth animation
                        dispatch();
                    });
                    google.maps.event.addListener( gmap, 'zoom_changed', function() { dispatch(); });
                    google.maps.event.addListener( marker, 'dragend', function() { dispatch() });

                    function dispatch() {
                        gmap_holder[0].dispatchEvent( new CustomEvent( 'map_changed', { detail: {
                            'gmap' : gmap,
                            'marker' : marker
                        }}));
                    }

                    gmap_holder[0].addEventListener( 'map_changed', function(e) { // ++this goes to the form script.js
                        // change the values immediately
                        //console.log( e.detail.gmap );
                        //marker.getPosition().lat();
                        //marker.getPosition().long();
                        //gmap.getZoom();
                    });
                    


                },
                //function() { fcAddGmapView( '.fct-gmap-view', true ) }, // ++restore this one to work as view
                ['google']
            );
        }
        
},300)}();
