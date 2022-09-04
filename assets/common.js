!function(){let a=setInterval(function(){let b=document.readyState;if(b!=='complete'&&b!=='interactive'||typeof jQuery==='undefined'){return}let $=jQuery;clearInterval(a);a=null;

        anchor_links();
        menu_events();

        let scrolled = window.scrollY;
        scrolled_monitor();

        $( window ).on( 'scroll', function() {
            scrolled_monitor();
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

        function scrolled_monitor() {
            const l = document.body.classList,
                  s = window.scrollY,
                  c = 'scrolled', d = 'scrollingDown';
            if ( s < 40 ) { l.remove( c, d ); return }
            l.add( c );
            if ( s < 300 ) { l.remove( d ); return }
            l.add( d );
            if ( s < scrolled ) { l.remove( d ) }
            else { l.add( d ) }
            scrolled = s;
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
                $heightObject = $( '.site-header' );
            if ( $heightObject.length ) {
                offset = $heightObject.height();
            }
            return offset;
        }
        
        function menu_events() {
            var $checkbox = $( '#nav-primary-toggle' ),
                $hamburger = $( '#nav-primary-toggle + .site-header .toggle-label' );

            $checkbox.click( function() {
                setTimeout( function() {
                    if ( $checkbox.prop( 'checked' ) ) {
                        document.addEventListener( 'click', menuHide );
                    }
                });
            });

            function menuHide(e) {
                if ( e.target === $hamburger[0] ) {
                    e.preventDefault();
                }
                $checkbox.prop( 'checked', false );
                document.removeEventListener( 'click', menuHide, false );
            }
        }
        
        /* toggle element after this one */
        $( '.fct1-open-next' ).click( function(e) {
            e.preventDefault();
            $( this ).toggleClass( 'active' );
        });

        /* add map by class | moved to borlabs */
/*
        const $gmap_holder = $( '.fct1-gmap-view' );
        if ( $gmap_holder.length ) {
            fcLoadScriptVariable(
                '/wp-content/themes/fct1/assets/smarts/fcOnVisibleDo.js',
                'fcOnVisibleDo', function() {

                fcOnVisibleDo( $gmap_holder, function() {
                    fcLoadScriptVariable(
                        'https://maps.googleapis.com/maps/api/js?key='+fcGmapKey+'&libraries=places', // ++unite global variables
                        'google'
                    );
                    fcLoadScriptVariable(
                        '/wp-content/themes/fct1/assets/smarts/gmap-view.js',
                        'fcAddGmapView',
                        function() { fcAddGmapView( $gmap_holder, true ) },
                        ['google']
                    );
                }, 150 );
                    
            }, ['jQuery'] );
        }
//*/
        /* add video by class */
        const $video_holder = $( '.fct1-video[data-source=direct]' ); // only direct videos, as youtube is in borlabs
        if ( $video_holder.length ) {
            fcLoadScriptVariable(
                '/wp-content/themes/fct1/assets/smarts/fcOnVisibleDo.js',
                'fcOnVisibleDo', function() {
                    fcOnVisibleDo( $video_holder, function(self) {
                        fcLoadScriptVariable(
                            '/wp-content/themes/fct1/assets/smarts/video.js',
                            'fcAddVideo',
                            function() { fcAddVideo( self ) }
                        );
                }, -200 );
            }, ['jQuery'] );
        }

},300)}();
