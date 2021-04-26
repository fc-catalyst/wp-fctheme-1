;(function() {

    var a = setInterval( function() {
        if ( document.readyState !== "complete" && document.readyState !== "interactive" ) {
            return;
        }
        if ( typeof window.jQuery === 'undefined' ) {
            return;
        }

        window.clearInterval( a );
        var $ = window.jQuery;

        /* tracking events */
        $( window ).scroll( function() {
            var scrolled = $( window ).scrollTop();

            body_add_scrolled( scrolled );

        });

        $( document ).ready( function() {
            anchor_links(); // or just keep the default scroll behavior
            menu_events();
        });

        /* the functions for the events above */
        function anchor_links() {
            var $anchors = $( 'a[href^="#"]:not([href="#"])' );
            if ( !$anchors.length ) {
                return;
            }
            $( 'head' ).append( '<style>html{scroll-behavior:auto!important;}</style>' );
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

        function body_add_scrolled(scrolled) {
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

            if ( typeof target ==='string' || typeof target ==='object' && !target instanceof $ ) {
                var $target = $( target );
            } else {
                var $target = target;
            }

            if ( !$target || !$target.length ) {
                return;
            }

            var scroll_to = $target.position()['top'] - scroll_offset();
            $( 'html, body' ).animate( {
                scroll_to_objectp: scroll_to
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
            var checkbox = $( '#nav-primary-toggle' ),
                hamburger = checkbox.next();

            checkbox.click( function() {
                setTimeout( function() {
                    if ( checkbox.prop( 'checked' ) ) {
                        document.addEventListener( 'click', menuHide );
                    }
                });
            });

            function menuHide(e) {
                if ( e.target === hamburger[0] ) {
                    e.preventDefault();
                }
                checkbox.prop( 'checked', false );
                document.removeEventListener( 'click', menuHide, false );
            }
        }
        
    });
} )();
