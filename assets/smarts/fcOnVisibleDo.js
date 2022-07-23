// fcOnVisibleDo() is the function to run a function if an object is in the vertical visibility range

(function() {
    let load = [],
        timer = setTimeout( ()=>{} );

    function add( obj, func, bias = 0 ) { // bias: +20 for later -20 for earlier
        if ( !obj || !func ) { return }

        const add = function(obj) {
            if ( typeof obj !== 'object' ) { return }
            if ( typeof jQuery !== 'undefined' && obj instanceof jQuery ) { obj = obj[0] }
            load.push( { o : obj, f : func, b : bias ? bias : 0, t : top( obj ) } );
        };
        
        if ( typeof obj ==='string' ) { document.querySelectorAll( obj ).forEach( add ) }
        if ( typeof obj === 'object' ) { add( obj ) }

        if ( load.length === 0 ) { return }

        start();
    }

    function check() {
        for ( let k in load ) {
            // ++can add comparing for scrolling up from below
            if ( window.scrollY + window.innerHeight < load[k].t + load[k].b ) { continue }
            load[k].f( load[k].o );
            load.splice(k, 1);
        }
        if ( load.length === 0 ) { stop() }
        
        clearTimeout( timer );
        timer = setTimeout( recount, 500 ); // recount every scroll-stop in case of something loads lazy
    }
    
    function recount() {
        for ( let k in load ) { load[k].t = top( load[k].o ) }
    }

    function top(obj) {
        return obj.getBoundingClientRect().top + window.scrollY;
    }
    
    function start() {
        stop();
        document.addEventListener( 'scroll', check );
        window.addEventListener( 'resize', recount ); // ++can replace with a bodyResize custom event to avoid rude^
        check();
    }
    function stop() {
        document.removeEventListener( 'scroll', check );
        window.removeEventListener( 'resize', recount );
    }

    window.fcOnVisibleDo = add; // object or selector, function to run, offset, use on loaded document
    window.fcOnVisibleDo.check = check; // can be attached to other events
    window.fcOnVisibleDo.recount = recount; // can be attached to other events

})();