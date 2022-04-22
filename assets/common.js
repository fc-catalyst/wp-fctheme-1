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
        $( '.fct1-open-next > a' ).click( function(e) {
            e.preventDefault();
            $( this ).parent().toggleClass( 'fct1-active' );
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
        const $gmap_holder = $( '.fct1-gmap-view' );
        if ( $gmap_holder.length ) {
            fcLoadScriptVariable(
                'https://maps.googleapis.com/maps/api/js?key='+fcGmapKey+'&libraries=places', // ++unite global variables
                'google'
            );
            fcLoadScriptVariable(
                '/wp-content/themes/fct1/assets/smarts/gmap-view.js?' + + new Date(),
                'fcAddGmapView',
                function() { fcAddGmapView( $gmap_holder, true ) },
                ['google']
            );
        }
        
},300)}();
