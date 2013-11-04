/**
 * iFrame Resize code from: https://github.com/johnymonster/iframe_height
 */
(function($){
    window.SlideDeckiFrameResize = function( options, ratio, proportional ){
        var el, iframe, i, script, messageHandler, element, container, xdomain,
        props = {
            src : '',
            width : '100%',
            style : 'padding: 0; margin: 0; border: none; display: block; height: 0; overflow: hidden;',
            scrolling : 'no',
            frameBorder : 0,
            id : ''
        };
        
        var rtime = new Date(1, 1, 2000, 12,00,00);
        var timeout = false;
        var delta = 120;
        var debounceMilliseconds = 150; // Minimum time between refreshes
        var widthDelta = 5; // At least this many px difference
        var startWidth;
        var endWidth;
        var startSlide = false;
        
        var ie = navigator.userAgent.toLowerCase().indexOf('msie') > -1;
        var ie9 = navigator.userAgent.toLowerCase().indexOf('msie 9') > -1;
        var ie10 = navigator.userAgent.toLowerCase().indexOf('msie 10') > -1;
    
        // Sets the height of the iframe
        function setHeight( message ) {
            var messageParts = message.split('__');
            var SlideDeckUniqueId = messageParts[0];
            var newHeight = messageParts[1];
            
            if( SlideDeckUniqueId == props.id ) {
                startSlide = messageParts[2];
                document.getElementById( props.id ).style.height = parseInt( newHeight ) + 'px';
            }
        }
        
        // Handler when window.postMessage is available
        function messageHandler(e) {
            var height, r,
            regex = new RegExp(xdomain + '$'),
            matches = e.origin.match(regex);
        
            if(matches) {
                if(matches.length == 1){
                    strD = e.data + "";
                    
                    setHeight(strD);
                }
            }
        }
        
        // Sets the default values then overrides
        function setProps( options, ratio ) {
            for (i in props) {
                try {
                    var prop = (props[i] == options[i] || typeof(options[i]) == "undefined")? props[i] : options[i];
                    if( i == 'id' ){
                        props.id = prop;
                        iframe.id = prop;
                    } else if (i !== 'style') {
                        iframe[i] = prop;
                    } else {
                        iframe[i].cssText = prop;
                    }
                } catch (ex) {}
            }
        }
        
        function setup( options, ratio ) {
            options = options || {};
            xdomain = options.domain || '*';
            element = options.element || 'iframe-embed';
            container = document.getElementById(element);
            el = ( !ie || ie9 || ie10 ) ? 'iframe' : '<iframe name="' + element + '" allowTransparency="true"></iframe>';
            iframe = document.createElement(el);
            setProps(options);
        }
        
        function resizeend() {
            if ( new Date() - rtime < delta) {
                setTimeout(resizeend, delta);
            } else {
                timeout = false;
                
                // Resize End
                endWidth = parseInt( jQuery('#' + props.id + '-wrapper').width() );
                
                var widthDiff = Math.abs( startWidth - endWidth );
                if( widthDiff > widthDelta ) {
                    var newHeight = parseInt(jQuery('#' + props.id + '-wrapper').height());
                    $('#' + props.id + '-wrapper iframe')[0].src = $('#' + props.id + '-wrapper iframe')[0].src
                    .replace(/outer_width=[0-9]+/,'outer_width=' + endWidth ).replace(/outer_height=[0-9]+/,'outer_height=' + newHeight )
                    .replace(/width=[0-9]+/, 'width=' + endWidth )
                    .replace(/height=[0-9]+/, 'height=' + newHeight )
                    .replace(/start=([0-9]+)?/, 'start=' + startSlide);
                }
                
                startWidth = endWidth;
            }
        }
        
        function load(options, ratio){
            setup(options);
            if(!container) return;
            try {
                container.appendChild(iframe);
                if (window.postMessage) {
                    if (window.addEventListener) {
                        window.addEventListener('message', messageHandler, false);
                    } else if (window.attachEvent) {
                        window.attachEvent('onmessage', messageHandler);
                    }
                } else {
                    setInterval(function () {
                        var hash = window.location.hash,
                            matches = hash.match(/^#message(.*)$/);
                        if (matches) {
                            setHeight(matches[1]);
                        }
                    }, debounceMilliseconds );
                }
            } catch (ey) {}
            
            // Bind the wrapper proportional resize
            if( proportional ){
                jQuery(window).bind('resize', function(event){
                    jQuery('#' + props.id + '-wrapper').css( 'height', parseInt( jQuery('#' + props.id + '-wrapper').width() * ratio ) );
                });
            }
            
            // Set the start width
            startWidth = parseInt( jQuery('#' + props.id + '-wrapper').width() );
            
            // Bind the resize event for debouncing
            $(window).resize(function() {
                rtime = new Date();
                if (timeout === false) {
                    timeout = true;
                    setTimeout(resizeend, delta);
                }
            });
        }
        
        // Load the iFrame and bind the associated events.
        load( options, ratio );
    };
})(jQuery);