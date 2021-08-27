;(function() {

    var a = setInterval( function() {
        if ( document.readyState !== "complete" && document.readyState !== "interactive" ) {
            return;
        }
        if ( typeof window.jQuery === 'undefined' ) {
            return;
        }

        window.clearInterval( a );
        var $ = window.jQuery,
            scrolled = 0;

        anchor_links();
        menu_events();

        scrolled = $( window ).scrollTop();
        body_add_scrolled();

        addGallery( '.fct-vertical-gallery' ); // ++ move to clinics template
        
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
        
        function addGallery(selector) {
            var $self = $( '.fct-vertical-gallery' ),
                $children = $self.children();

            // navigation buttons
            $self.before( '<div class="fct-vertical-gallery-up"></div>' );
            $self.after( '<div class="fct-vertical-gallery-down"></div>' );
            var $up = $self.prev( '.fct-vertical-gallery-up' ),
                $down = $self.next( '.fct-vertical-gallery-down' );
            
            $down.click( function() {
                var visible_height = Math.round( $self.parent().innerHeight() - $self.position()['top'] );

                if ( visible_height > $self.outerHeight() ) { return; }

                var cheights = [],
                    vpheight = Math.max( document.documentElement.clientHeight || 0, window.innerHeight || 0 ),
                    offset_now = Math.round( Number( $self.attr( 'data-offset' ) ? $self.attr( 'data-offset' ) : 0 ) ),
                    offset_max_step = Math.min.apply( null, [
                        Math.round( visible_height * 3 / 4 ),
                        Math.round( vpheight * 3 / 4 )
                    ]),
                    new_step = 0,
                    cheight_above = visible_height + offset_now;
                
                $children.each( function(a, b) { cheights[a] = Math.floor( $( b ).outerHeight( true ) ); } );
                
                // count new offset
                for ( let i = 0, a = 0, add = 0; i < cheights.length; i++ ) {
                    a = cheights[i];
                    add += a;

                    if ( add <= cheight_above ) { continue; }

                    new_step = add - cheight_above;

                    if ( new_step > offset_max_step && new_step !== a ) {
                        new_step = new_step - a;
                        break;
                    }
                    
                    if ( i === cheights.length - 1 ) {
                        $down.hide();
                    }
                }
                $up.show();

                if ( !new_step ) { return; }

                addGallery.process( offset_now + new_step );

            });

            $up.click( function() {

                addGallery.process( 0 );
                
                $up.hide();
                $down.show();
            });
            
            // the motion process
            addGallery.process = function(offset_new) {
                $children.each( function( a, b ) {
                    $( b ).css( 'transform', 'translate( 0, -' + offset_new + 'px )' );
                });
                $self.attr( 'data-offset', offset_new );
            };
 
            // navigation buttons show-hide
            addGallery.reset = function() {
                addGallery.process( 0 );
                
                $up.hide();
                $down.hide();
                if ( $self.parent().innerHeight() - $self.position()['top'] > $self.outerHeight() ) { return; }
                $down.show();
            };
            addGallery.reset();
            $( window ).resize( function() { addGallery.reset(); });

        }
       
    });
} )();
