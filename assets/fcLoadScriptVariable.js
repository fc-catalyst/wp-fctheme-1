// fcLoadScriptVariable() is the function to load js files & variables on condition

(function() {
    let load = [],
        paths = [], // for faster search
        interval = function(){},
        timer = setTimeout( function(){} ),
        tumbler = false;

    function init( path = '', variable = '', func = function(){}, dependencies = [], css = false ) {
        if ( !path && !variable ) { return }
        path = path && !~path.indexOf( '?' ) ? path + '?ver=' + fcVer : path; // add version for static scripts
        //++ the version can be provided by a plugin - add one more custom argument to this function
        load.push( { p : path, v : variable, f : func, d : dependencies, c : css } );
        start();
    }

    function start() {
        if ( !tumbler ) {
            interval = setInterval( process, 300 ); // recheck every X ms
            tumbler = true;
        }
        clearTimeout( timer ); // reset the max waiting time
        timer = setTimeout( stop, 20000 ); // max waiting time
    }
    function stop() {
        clearInterval( interval );
        tumbler = false;
        clearTimeout( timer );
    }

    function loaded(e) {
        paths[ e.target.getAttribute( 'src' ) ] = 2;
    }
    
    function process() {

        if ( !load.length ) { stop(); return }
        if ( !( document.readyState === 'complete' || document.readyState === 'interactive' ) ) { return }
        
        const setAtts = function(tag, atts = {}, onload = false) {
            let el = document.createElement( tag );
            for ( let k in atts ) {
                el.setAttribute( k, atts[k] );
            }
            if ( atts.src && onload && typeof onload === 'function' ) {
                el.addEventListener( 'load', onload );
            }
            document.head.appendChild( el );
        };

        mainloop: for ( let k in load ) {

            // start loading only after dependencies global variables appear
            for ( let i = 0, j = load[k].d.length; i < j; i++ ) {
                if ( typeof window[ load[k].d[i] ] === 'undefined' ) { continue mainloop }
            }

            // load js & css paths
            if ( load[k].p && !paths[ load[k].p ] ) {

                paths[ load[k].p ] = 1; // 1 = tag added, 2 = path loaded

                setAtts( // ++ can check if files exist first to avoid the error printing https://stackoverflow.com/questions/3646914/how-do-i-check-if-file-exists-in-jquery-or-pure-javascript
                    'script',
                    {
                        'type' : 'text/javascript',
                        'src' : load[k].p,
                        'async' : ''
                    },
                    loaded
                );

                let css = load[k].c === true ? load[k].p.replace( '.js', '.css' ) : load[k].c;
                if ( css ) {
                    setAtts( 'link', { 'type' : 'text/css', 'href' : css, 'rel' : 'stylesheet' } );
                }
            }

            // path is loaded
            if ( load[k].p && paths[ load[k].p ] !== 2 ) { continue }

            // variable is loaded
            if ( load[k].v && typeof window[ load[k].v ] === 'undefined' ) { continue }

            load[k].f();
            load.splice(k, 1);
        }
    }

    window.fcLoadScriptVariable = init; // url, global variable, function, [loaded variables], load url.css

})();