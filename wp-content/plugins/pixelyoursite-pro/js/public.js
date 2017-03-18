jQuery(document).ready(function( $ ) {

    var utm_terms = [ 'utm_source', 'utm_media', 'utm_campaign', 'utm_term', 'utm_content' ];

    // load FB pixel
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
        document,'script','https://connect.facebook.net/en_US/fbevents.js');

    manageCookies();

    regularEvents();
    dynamicEvents();
    customCodeEvents();

    // temporary disabled due CORS error (@see #55 issue)
    // @since 4.0.5
    //timeOnPageEvent();

    // AddToCart button
    $(".ajax_add_to_cart").click(function(e){

        var attr = $(this).attr('data-pys-event-id');

        if( typeof attr == 'undefined' || typeof pys_woo_ajax_events == 'undefined' ) {
            return;
        }

        evaluateEventByID( attr.toString(), pys_woo_ajax_events );

    });

    // Affiliate Product
    $(".product_type_external").click(function(e){

        var attr = $(this).attr('data-pys-event-id');

        if( typeof attr == 'undefined' || typeof pys_woo_ajax_events == 'undefined' ) {
            return;
        }

        evaluateEventByID( attr.toString(), pys_woo_ajax_events );

    });

    // PayPal Event
    $(document).on('submit click', '#place_order', function(e){

        var method = $('input[name="payment_method"]:checked').val();

        if( method == false || method != 'paypal' ) {
            return;
        }

        try {

            var eventData = JSON.parse($(this).attr('data-pys-code'));

            var params = addTrafficSourceParams(eventData.type, eventData.params);

            fbq(eventData.type, eventData.name, params);

        } catch (e) {
            console.log(e);
        }

    });

    // EDD AddToCart
    $('.edd-add-to-cart').click(function () {

        try {
            
            // extract pixel event ids from classes like 'pys-event-id-{UNIQUE ID}'
            var classes = $.grep(this.className.split(" "), function ( element, index ) {
                return element.indexOf('pys-event-id-') === 0;
            });
    
            // verify that we have at least one matching class
            if (typeof classes == 'undefined' || classes.length == 0) {
                return;
            }
    
            // extract event id from class name
            var regexp = /pys-event-id-(.*)/;
            var event_id = regexp.exec(classes[0]);
    
            if( event_id == null ) {
                return;
            }
            
            evaluateEventByID(event_id[1], pys_edd_ajax_events);

        } catch (e) {
            console.log(e);
        }

    });

    /**
     * Process Init, General, Search, Standard (except custom code), WooCommerce (except AJAX AddToCart, Affiliate and
     * PayPal events. In case if delay param is present - event will be fired after desired timeout.
     */
    function regularEvents() {

        if( typeof pys_events == 'undefined' ) {
            return;
        }

        for( var i = 0; i < pys_events.length; i++ ) {

            var eventData = pys_events[i];
            var params = addTrafficSourceParams( eventData.type, eventData.params );

            if( eventData.hasOwnProperty('delay') == false || eventData.delay == 0 ) {

                fbq( eventData.type, eventData.name, params );

            } else {

                setTimeout( function( type, name, params ) {
                    fbq( type, name, params );
                }, eventData.delay * 1000, eventData.type, eventData.name, params );

            }


        }

    }

    function timeOnPageEvent() {

        if( typeof pys_timeOnPage == 'undefined' ) {
            return;
        }

        var counter = 0;
        setInterval(function(){
            counter += 1;
        }, 1000 );

        // setup event listener
        window.onbeforeunload = function(e) {

            pys_timeOnPage.time = counter;

            var params = addTrafficSourceParams( 'track', pys_timeOnPage );
            fbq( 'trackCustom', 'TimeOnPage', params );

        };

    }

    /**
     * Process only custom code Standard events.
     */
    function customCodeEvents() {

        if( typeof pys_customEvents == 'undefined' ) {
            return;
        }

        $.each( pys_customEvents, function( index, code ) {
            eval( code );
        } );

    }

    /**
     * Attach Dynamic events event handlers:
     * - on click: for URL event type (url will has .pys-dynamic-event class);
     * - on click or on mouseenter: for CSS event type;
     */
    function dynamicEvents() {

        // attach dynamic event listeners on URL clicks
        $('.pys-dynamic-event').onFirst('click', function () {

            // Non-default binding used to avoid situations when some code in external js
            // stopping events propagation, eg. returns false, and our handler will never called.
            //
            // Before we used:
            // $(document).on('click', '.pys-dynamic-event', function (e) {

            var attr = $(this).attr('data-pys-event-id');

            if( typeof attr == 'undefined' ) {
                return;
            }

            evaluateEventByID( attr.toString(), pys_dynamic_events );

        });

        // on Click by css selector
        if( typeof pys_css_selectors != 'undefined' ) {

            // setup event listeners for each selector
            $.each( pys_css_selectors, function( eventID, selector ) {

                // $(document).on('click', selector, function () {
                //     evaluateEventByID( eventID, pys_dynamic_events );
                // });

                // Non-default binding used to avoid situations when some code in external js
                // stopping events propagation, eg. returns false, and our handler will never called
                $(selector).onFirst('click', function () {
                    evaluateEventByID(eventID, pys_dynamic_events);
                });

            });

        }

        // on page Scroll by desired position
        if( typeof pys_scroll_positions != 'undefined' ) {

            var height = $(document).height() - $(window).height();

            // collect al thresholds values
            var thresholds = {};
            $.each( pys_scroll_positions, function( eventID, pos ) {

                // convert % to pixels
                pos = parseInt( pos );
                pos = height * pos / 100;
                pos = Math.round( pos );

                // convert to correct field name (numbers can't be used)
                pos = '_' + pos.toString();

                // add new position
                if( typeof thresholds[pos] == 'undefined' ) {
                    thresholds[pos] = [];
                }

                // array is used because many events can be attached to same scroll position
                // so each position has an array with events ids
                thresholds[pos].push( eventID );

            });

            $(document).scroll(function () {

                var scroll = $(window).scrollTop();

                $.each( thresholds, function( pos, events ) {

                    var trigger = parseInt( pos.substring(1) ); // integer threshold value

                    // position is not reached
                    if( scroll <= trigger ) {
                        return true;
                    }

                    // fire all events in current threshold
                    $.map( events, function( eventID, index ) {

                        if( events[ index ] != null ) {

                            evaluateEventByID( eventID, pys_dynamic_events );
                            events[ index ] = null; // do not fire event next time

                        }

                    });


                });

            });

        }

        // on Mouse Over by css selectors
        if( typeof pys_mouse_over_selectors != 'undefined' ) {

            // setup event listeners for each selector
            $.each( pys_mouse_over_selectors, function( eventID, selector ) {

                $(document).on('mouseover', selector, function () {

                    evaluateEventByID( eventID, pys_dynamic_events );

                    // event should be fired only once
                    delete pys_dynamic_events[ eventID ];

                });

            });

        }

    }

    /**
     * Fire event with {eventID} from =events= events list. In case of event data have =custom= property, code will be
     * evaluated without regular Facebook pixel call.
     */
    function evaluateEventByID( eventID, events ) {

        if( typeof events == 'undefined' || events.length == 0 ) {
            return;
        }

        // try to find required event
        if( events.hasOwnProperty( eventID ) == false ) {
            return;
        }

        var eventData = events[ eventID ];

        if( eventData.hasOwnProperty( 'custom' ) ) {

            eval( eventData.custom );

        } else {

            var params = addTrafficSourceParams( eventData.type, eventData.params );
            fbq( eventData.type, eventData.name, params );

        }

    }

    function getTrafficSource() {

        try {

            var referrer = document.referrer.toString();

            var direct = referrer.length == 0;
            var internal = direct ? false : referrer.indexOf(pys_options.site_url) === 0;
            var external = !(direct || internal);
            var cookie = typeof Cookies.get('pys_traffic_source') == 'undefined' ? false : Cookies.get('pys_traffic_source');

            if (external == false) {

                return cookie ? cookie : 'direct';

            } else {

                return cookie && cookie == referrer ? cookie : referrer;

            }
            
        } catch (e) {
            console.log(e);
        }

    }

    /**
     * Return UTM terms from request query variables or from cookies.
     */
    function getUTMs() {

        try {

            var terms = {};
            var queryVars = getQueryVars();

            $.each(utm_terms, function (index, name) {

                if (Cookies.get('pys_' + name)) {
                    terms[name] = Cookies.get('pys_' + name);
                } else if (queryVars.hasOwnProperty(name)) {
                    terms[name] = queryVars[name];
                }

            });

            return terms;
            
        } catch (e) {
            console.log(e);
        }

    }

    function manageCookies() {

        try {

            var source = getTrafficSource();

            if (source != 'direct') {
                Cookies.set('pys_traffic_source', source);
            } else {
                Cookies.remove('pys_traffic_source');
            }

            var queryVars = getQueryVars();

            $.each(utm_terms, function (index, name) {

                if (Cookies.get('pys_' + name) == undefined && queryVars.hasOwnProperty(name)) {
                    Cookies.set('pys_' + name, queryVars[name]);
                }

            });
            
        } catch (e) {
            console.log(e);
        }

    }

    /**
     * Add =traffic_source= and =utm_***= params to existing event params for all events except =init= event and
     * in case of option is enabled. Returns modified =params= object.
     */
    function addTrafficSourceParams( type, params ) {

        try {

            if (pys_options.traffic_source_enabled == false || type == 'init') {
                return params;
            }

            params.traffic_source = getTrafficSource();

            $.each(getUTMs(), function (name, value) {

                if ($.inArray(name, utm_terms) >= 0) {
                    params[name] = value;
                }

            });

            return params;
            
        } catch (e) {
            console.log(e);
        }
        

    }

    /**
     * Return query variables object with where property name is query variable and property value is query variable value.
     */
    function getQueryVars() {

        try {

            var result = {}, tmp = [];

            window.location.search
                .substr(1)
                .split("&")
                .forEach(function (item) {

                    tmp = item.split('=');

                    if (tmp.length > 1) {
                        result[tmp[0]] = tmp[1];
                    }

                });

            return result;
            
        } catch (e) {
            console.log(e);
        }

    }

});