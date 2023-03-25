/**
 * Contempo Mapping
 *
 * @package WP Pro Real Estate 7
 * @subpackage JavaScript
 */

/*****************************************************
 *
 * Start Map Draw/Pan/Zoom/Scroll Update Markers
 *
 ******************************************************/

var marker_list = [];
var mapPin = '';
var map = null;
var poly = null;

var storedLongitude = "";
var storedLatitude = "";

var open_info_window = null;

var mapCenterLat = "";
var mapCenterLng = "";

var mapNorthEast = "";
var mapNorthWest = "";
var mapSouthEast = "";
var mapSouthWest = "";

var boundsChanged = false;

var hitCount = 0;

var watchDog = 0;

var x_info_offset = -0; // x,y offset in px when map pans to marker -- to accomodate infoBubble
var y_info_offset = -180;
var debounceTime = 250;
var drawingMode = false;
var drawListener;
var markerCluster;
var drawMarkers;
var drawBounds;
var oldZoom;
var oldBounds;

google.maps.LatLng.prototype.distanceFrom = function (latlng) {
    var lat = [this.lat(), latlng.lat()]
    var lng = [this.lng(), latlng.lng()]
    var R = 6378137;
    var dLat = (lat[1] - lat[0]) * Math.PI / 180;
    var dLng = (lng[1] - lng[0]) * Math.PI / 180;
    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(lat[0] * Math.PI / 180) * Math.cos(lat[1] * Math.PI / 180) *
        Math.sin(dLng / 2) * Math.sin(dLng / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var d = R * c;
    return Math.round(d);
}

jQuery(document).on("click", ".cancelDrawButton", function (e) {
    cancelDrawing();
    disableDrawMode();
    google.maps.event.removeListener(drawListener);
    //jQuery('.draw-mode').find('span').html('Draw');
    jQuery('#ct-gmap-draw').removeClass('draw-mode').find('span').html('');
});

jQuery(document).on("click", ".pagination a", function (e) {

    var value = jQuery(this).attr("href");
    let order_by = jQuery("select#ct_orderby").val();

    if (value.indexOf("?") < 0) {
        return;
    }

    value = value.substring(value.indexOf("?") + 1);

    if (getUrlParameter("action", value) == "map_listing_update") {
        jQuery("#number-listings-progress").css("display", "block");
        e.preventDefault();

        ne = getUrlParameter("ne", value);
        ne = jQuery("<div />").html(ne).text(); // unescape it
        ne = ne.replace("+", " ");

        sw = getUrlParameter("sw", value);
        sw = jQuery("<div />").html(sw).text(); // unescape it
        sw = sw.replace("+", " ");

        paged = getUrlParameter("paged", value);

        doAjax(ne, sw, paged, () => {}, {ct_orderby: order_by });

        jQuery("html, body").animate({scrollTop: 0}, "slow");
    }

    if (poly != null) {
        jQuery("#number-listings-progress").css("display", "block");
        e.preventDefault();
        paged = parseInt(jQuery(this).html());
        doAjax('', '', paged, () => {}, {ct_orderby: order_by });
        jQuery("html, body").animate({scrollTop: 0}, "slow");
    }

});

jQuery(document).on("mouseenter", ".side-results .listing", function (e) {

    var listingID = jQuery(this).attr('data-listing-id');

    if (open_info_window) {
        open_info_window.close();
        jQuery('img[src="' + open_info_window.closeBoxURL_ + '"]').click();
    }

    if (undefined != listingID) {
        for (var i = 0; i < marker_list.length; i++) {

            if (parseInt(marker_list[i].listingID, 10) === parseInt(listingID, 10)) {
                extraClass = '';
                tempMarker = jQuery("img[src='" + marker_list[i].iconURL + "']").parent();
                if (marker_list[i].priceDisplay == 'false') {
                    extraClass = 'no-price-display';
                }
                if (tempMarker.find('.markerClass').length > 0) {
                    tempMarker.find('.markerClass').first().addClass('ct-active-marker');
                } else {
                    tempMarker.append('<div class="markerClass ct-active-marker ' + marker_list[i].ctStatus + ' ' + extraClass + '"></div>');
                }

                if (marker_list[i].infobox) {

                    var northWest = new google.maps.LatLng(map.getBounds().getNorthEast().lat(), map.getBounds().getSouthWest().lng());
                    var southEast = new google.maps.LatLng(map.getBounds().getSouthWest().lat(), map.getBounds().getNorthEast().lng());
                    var SWdistance = map.getBounds().getSouthWest().distanceFrom(marker_list[i].getPosition());
                    var NEdistance = map.getBounds().getNorthEast().distanceFrom(marker_list[i].getPosition());
                    var NWdistance = northWest.distanceFrom(marker_list[i].getPosition());
                    var SEdistance = southEast.distanceFrom(marker_list[i].getPosition());

                    var minDistance = Math.min(SWdistance, NEdistance, NWdistance, SEdistance);

                    switch (minDistance) {
                        case SEdistance:
                            ibOffset = new google.maps.Size(-300, 150);
                            //console.log('SE');
                            break;
                        case NEdistance:
                            ibOffset = new google.maps.Size(-300, 250);
                            //console.log('NE');
                            break;
                        case NWdistance:
                            ibOffset = new google.maps.Size(50, 300);
                            //console.log('NW');
                            break;
                        case SWdistance:
                            ibOffset = new google.maps.Size(50, 100);
                            //console.log('SW');
                            break;
                    }

                    marker_list[i].infobox.setOptions({'pixelOffset': ibOffset});
                    marker_list[i].infobox.open(map, marker_list[i]);
                    open_info_window = marker_list[i].infobox;
                }

            }
        }

    }

});

jQuery(document).on("mouseleave", ".side-results .listing, .listing-search-results .listing", function (e) {

    var listingID = jQuery(this).attr('data-listing-id');

    if (undefined != listingID) {
        for (var i = 0; i < marker_list.length; i++) {

            if (parseInt(marker_list[i].listingID, 10) === parseInt(listingID, 10)) {

                jQuery('.ct-active-marker').removeClass('ct-active-marker');

                if (open_info_window) {
                    open_info_window.close();
                    jQuery('img[src="' + open_info_window.closeBoxURL_ + '"]').click();
                } else {
                    if (marker_list[i].infobox) {
                        marker_list[i].infobox.close(map);
                    }
                }

                break;

            }
        }

    }

});

function getUrlParameterObject(sPageURL = "") {
    if (sPageURL == "") {
        sPageURL = window.location.search.substring(1);
    }

    var sURLVariables = sPageURL.split('&'), sParameterName, i;

    returnObject = {};

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        key = sParameterName[0];
        value = sParameterName[1] === undefined ? "" : decodeURIComponent(sParameterName[1]);

        if (value != "") {

            returnObject = Object.assign({[key]: value}, returnObject)

        }

    }

    return returnObject;
}

function getUrlParameter(sParam, sPageURL) {
    if (sPageURL == "") {
        sPageURL = window.location.search.substring(1);
    }

    var sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? false : decodeURIComponent(sParameterName[1]);
        }
    }

    return false;
}

function getUsersLocation() {

    // Check if user enabled/disabled the geolocation.
    if ( typeof mapping_ajax_object !== "undefined" ) {
        if ( mapping_ajax_object.hasOwnProperty('ct_listing_search_enabled') ) {
            if ( "yes" === mapping_ajax_object.ct_listing_search_enabled ) {
                return;
            }
        }
    }

    // This function only works over https and only if user consents.
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            // Success function
            getCoords
        );
    }
}

function getCoords(position) {

    storedLongitude = position.coords.longitude;
    storedLatitude = position.coords.latitude;

    jQuery("#search-latitude").val(storedLatitude);
    jQuery("#search-longitude").val(storedLongitude);

    //console.log(storedLatitude, storedLongitude);

    jQuery("#submit").trigger("click");
}

function submitUserLocation() {
    jQuery("#submit").trigger("click");
}

jQuery("#search-by-user-location").click(function () {
    getUsersLocation();
    //console.log('Geolocation clicked');
});

jQuery(document).on("click", ".sale-lease-links a", function (e) {

    if (storedLatitude == "" || storedLongitude == "") {
        return;
    }

    e.preventDefault();

    IsplaceChange = true;
    window.location.href = this.href + "&lat=" + storedLatitude + "&lng=" + storedLongitude;

});

function debounce(fn, time) {
    let theTimeout;
    return function () {
        const args = arguments;
        const functionCall = () => fn.apply(this, args);
        clearTimeout(theTimeout);
        theTimeout = setTimeout(functionCall, time);
    }
}

/***
 * returns -1 if not exists or the index if it does exist
 */
function markerExistsByLatLng(lat, lng, currentIndex = -1) {

    l = new google.maps.LatLng(lat, lng);

    for (y = 0; y < marker_list.length; y++) {
        test = marker_list[y];

        if (l.equals(test.getPosition())) {
            return y;
        }
    }

    return -1;

}

/***
 * returns -1 if not exists or the index if it does exist
 */
function markerExistsByMarker(marker, currentIndex = -1) {

    if (currentIndex == -1) {
        currentIndex = 0;
    } else {
        currentIndex++;
    }

    for (x = currentIndex; x < marker_list.length; x++) {
        test = marker_list[x];
        if (marker.getPosition().equals(
            test.getPosition()
        )) {
            return x;
        }
    }

    return -1;
}

function removeDuplicateMarkers() {
    indexesToRemove = new Array();
    markerLength = marker_list.length;

    for (gf = 0; gf < markerLength; gf++) {
        m = marker_list[gf];

        if (markerExistsByMarker(m, gf) > -1) {
            m.setMap(null);
            indexesToRemove.push(gf);
        }
    }

    for (gf = 0; gf < indexesToRemove.length; gf++) {
        marker_list.splice(indexesToRemove[gf], 1);
    }

}

function doAjax(ne, sw, __paged = 1, __callback, url_params) {

    var data = {};

    data = getUrlParameterObject();
    
    // Fix paging when ?paged is set.
    if ( data.paged ) {
        data.paged = __paged;
    }

    data = Object.assign({action: 'map_listing_update'}, data);
    data = Object.assign({ne: ne.toString()}, data);
    data = Object.assign({sw: sw.toString()}, data);
    data = Object.assign({paged: __paged}, data);
    
    // Listing status multi.
    data = Object.assign({ct_ct_status_multi: re7_localized_multiple_filters.filters.ct_ct_status_multi}, data);
    // City multi.
    data = Object.assign({ct_city_multi: re7_localized_multiple_filters.filters.ct_city_multi}, data);
    // Listing additional features.
    data = Object.assign({ct_additional_features: re7_localized_multiple_filters.filters.ct_additional_features}, data);
    // Listing property type.
    data = Object.assign({ct_property_type: re7_localized_multiple_filters.filters.ct_property_type}, data);

    //console.log(data);

    // Fix issue with `ct_orderby` not changing when the dropdown changes if there is a GET parameter of `ct_orderby`.
    if ( data.hasOwnProperty('ct_orderby') ) {
        // Delete the the ct_orderby prop.
        delete data.ct_orderby;
    }

    // If the drawing mode is enabled.
    if (drawMarkers != null && drawMarkers.length > 0) {
        data = Object.assign({drawMode: true}, data);
        data = Object.assign({drawMarkers: JSON.stringify(drawMarkers)}, data);
        //drawBounds = new google.maps.LatLngBounds();
    }

    url = mapping_ajax_object.ajax_url;

    // Check if url_params is an actual object and is not null.
    if ( typeof url_params === 'object' && url_params !== null ) {
        // Assign custom properties to data.
        data = Object.assign(url_params, data);
        // But overwrite the page.
        data = Object.assign({paged: __paged}, data);
    }

    jQuery.get(url, data, function (response) {

        if (response !== "") {

            dataArray = response;

            if ( ( typeof dataArray.count !== null ) && dataArray.count === 0 ) {
                console.info("Async Request Done. Query done. No listings found.");
            }

            document.getElementById("search-listing-mapper").innerHTML = dataArray.listings;

            document.getElementById("number-listings-found").innerHTML = dataArray.count;

            siteURL = dataArray.siteURL;

            if ( dataArray.map != "" ) {

                latlngs = dataArray.latlngs;

                var imagesURL;

                while (marker_list.length) {
                    marker_list.pop().setMap(null);
                }

                marker_list = [];

                // Clear all clusters.
                markerCluster.clearMarkers();

                for (x = 0; x < latlngs.length; x++) {

                        imagesURL = latlngs[x].property.siteURL;

                        var mapPin = getMapPin(latlngs[x].property);
                        enableMarkerPrice = mapping_ajax_object.ct_enable_marker_price;
                        priceZoomLevel = parseInt(mapping_ajax_object.search_marker_price_zoom_level);
                        priceLabel = '';

                        //if ( priceZoomLevel <= map.getZoom() && enableMarkerPrice == 'yes' && mapping_ajax_object.listing_marker_type == 'svg' ) {
                        if (showMarkerWithPrice(latlngs[x].property)) {
                            priceLabel = {
                                text: latlngs[x].property.markerPrice,
                                color: '#222',
                                fontSize: '11px',
                                fontWeight: 'normal'
                            };
                        }

                        var marker = new google.maps.Marker({
                            animation: null,
                            draggable: false,
                            flat: true,
                            fullScreenControl: true,
                            fullscreenControlOptions: {
                                position: google.maps.ControlPosition.BOTTOM_LEFT
                            },
                            icon: mapPin,
                            position: {lat: parseFloat(latlngs[x].lat), lng: parseFloat(latlngs[x].lng)},
                            listingID: latlngs[x].property.listingID,
                            iconURL: mapPin.url,
                            label: priceLabel,
                            ctStatus: latlngs[x].property.ctStatus,
                            priceDisplay: latlngs[x].property.priceDisplay,
                        });

                        marker_list.push(marker);

                        currentMarker = marker_list.length - 1;

                        var imagesURL = latlngs[x].property.siteURL;

                        var contentString = getInfoBoxContentString(latlngs[x].property);

                        if (mapping_ajax_object.listing_marker_type == 'svg') {
                            if (priceZoomLevel <= map.getZoom() && enableMarkerPrice == 'yes') {
                                pixOff = new google.maps.Size(-125, (mapping_ajax_object.ct_listing_marker_svg_size * -1) - 50);
                            } else {
                                pixOff = new google.maps.Size(-125, (mapping_ajax_object.ct_listing_marker_svg_size * -1) - 30);
                            }
                        } else {
                            pixOff = new google.maps.Size(-125, -64);
                        }

                        marker_list[currentMarker].infobox = new InfoBox({
                            content: contentString,
                            disableAutoPan: true,
                            maxWidth: 0,
                            alignBottom: true,
                            pixelOffset: pixOff,
                            zIndex: null,
                            closeBoxMargin: "8px 0 -20px -20px",
                            closeBoxURL: imagesURL + '/images/infobox-close.png',
                            infoBoxClearance: new google.maps.Size(1, 1),
                            isHidden: false,
                            pane: "floatPane",
                            enableEventPropagation: false
                        });

                        google.maps.event.addListener(marker, 'click', (function (marker, currentMarker) {

                            return function () {
                                marker_list[currentMarker].infobox.open(map, this);
                                map.panTo(convert_offset(marker_list[currentMarker].position, x_info_offset, y_info_offset));
                                //map.setCenter(marker_list[currentMarker].position);

                                if (open_info_window) {
                                    open_info_window.close();
                                }

                                open_info_window = marker_list[currentMarker].infobox;

                            }

                        })(marker, currentMarker));

                }
                // Reposition markers if by small offset when have the same location.
                map_markers_same_loc_reposition();
                //removeDuplicateMarkers();

                var markerClustererOptions = {
                    ignoreHidden: true,
                    /*maxZoom: mapping_ajax_object.search_cluster_zoom_level,*/
                    maxZoom: 18,
                    styles: [{
                        textColor: '#ffffff',
                        url: siteURL + '/images/cluster-icon.png?ver=1.0.1',
                        height: 48,
                        width: 48
                    }]
                };

                markerCluster = new MarkerClusterer(map, marker_list, markerClustererOptions);

            }

            if (drawMarkers != null && drawMarkers.length > 0) {
                disableDrawMode();

                if (drawBounds != null) {

                    map.fitBounds(drawBounds);
                }
            }
            // Add some cool callback here.
            if (typeof __callback === "function") {
                __callback();
            }
        }

        document.getElementById("number-listings-progress").style.display = "none";

        manageFeaturedTags();

    }, "json");
}

function getMapBounds(map) {
    //console.log("in getMapBounds");
    if (poly == null) {

        progressDiv = document.getElementById("number-listings-progress");

        if (!progressDiv) {
            // not on the map page
            return;
        }

        var bounds = map.getBounds();
        var ne = bounds.getNorthEast();
        var sw = bounds.getSouthWest();

        // already in an event..
        if (boundsChanged == true) {
            return;
        }

        progressDiv.style.display = "inherit";

        if (mapNorthEast != ne) {
            boundsChanged = true;
        }


        if (mapSouthWest != sw) {
            boundsChanged = true;
        }

        if (boundsChanged == true) {

            //console.log("doing it... hitCount: " + hitCount);

            if (open_info_window) {
                open_info_window.close();
            }

            if (hitCount == 0) {
                hitCount++;
                // we ignore the initial event on page load
                setTimeout(function () {
                    boundsChanged = false;
                }, 250);

                document.getElementById("number-listings-progress").style.display = "none";

                return;
            }

            let order_by = jQuery("select#ct_orderby").val();

            doAjax( ne, sw, 1, () => {}, {ct_orderby: order_by } );

            setTimeout(function () {
                boundsChanged = false;
            }, 250);

            jQuery(".pagination").hide();


        }
    }
}

function getInfoBoxContentString(property) {

    var svgPriceClass = '';

    if (showMarkerWithPrice(property)) {
        svgPriceClass = 'ct-marker-svg-price';
    }

    if (property['contactpage'] == 'contactpage') {

        if (property['thumb'] != '') {

            return '<div class="infobox ' + svgPriceClass + '">' +
                '<div class="info-image"' +
                    '<figure>' +
                        '<img src="' + property.thumb + '" height="250" width="250" />' +
                    '</figure>' +
                '</div>' +
                '<div class="listing-details">' +
                    '<header>' +
                        '<h4 class="marT0">' + property.street + '</h4>' +
                    '</header>' +
                    '<p class="price marB0"><strong><a href="//maps.google.com/maps?daddr=' + property.street + '" target="_blank">Driving Directions</a></strong></p>' +
                '</div>';

        } else {

            return '<div class="infobox ' + svgPriceClass + '">' +
                '<div class="listing-details">' +
                    '<header>' +
                        '<h4 class="marT0">' + property.title + '</h4>' +
                    '</header>' +
                    '<p class="price marB0"><strong><a href="//maps.google.com/maps?daddr=' + property.street + '" target="_blank">Driving Directions</a></strong></p>' +
                '</div>';

        }

    } else {

        if (property['commercial'] == 'commercial' || property['industrial'] == 'industrial' || property['retail'] == 'retail') {

            return '<div class="infobox ' + svgPriceClass + '">' +
                '<div class="info-image"' +
                    '<figure>' +
                        '<a onclick="ct_load_single_listing(event)" class="listing-link" rel="'+property.listingID+'" href="' + property.permalink + '">' +
                            property.thumb +
                        '</a>' +
                    '</figure>' +
                '</div>' +
                '<div class="listing-details">' +
                    '<header>' +
                        '<h4 class="marT5 marB5 price"><a onclick="ct_load_single_listing(event)" class="listing-link" rel="'+property.listingID+'" href="' + property.permalink + '">' + property.fullPrice + '</a></h4>' +
                        '<p class="listing-icons muted marB0"><svg id="ico-size-commercial" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="14" height="14" viewBox="0 0 20 20"> <path d="M17.5 3h-8.5v-0.5c0-0.827-0.673-1.5-1.5-1.5h-2c-0.827 0-1.5 0.673-1.5 1.5v0.5h-2.5c-0.827 0-1.5 0.673-1.5 1.5v2c0 0.827 0.673 1.5 1.5 1.5h2.5v10.5c0 0.827 0.673 1.5 1.5 1.5h2c0.827 0 1.5-0.673 1.5-1.5v-10.5h8.5c0.827 0 1.5-0.673 1.5-1.5v-2c0-0.827-0.673-1.5-1.5-1.5zM5 2.5c0-0.276 0.224-0.5 0.5-0.5h2c0.276 0 0.5 0.224 0.5 0.5v0.5h-3v-0.5zM8 10h-0.5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h0.5v2h-0.5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h0.5v2h-0.5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h0.5v1.5c0 0.276-0.224 0.5-0.5 0.5h-2c-0.276 0-0.5-0.224-0.5-0.5v-10.5h3v2zM18 6.5c0 0.276-0.224 0.5-0.5 0.5h-1.5v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-2v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-2v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-2v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-2v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-1.5c-0.276 0-0.5-0.224-0.5-0.5v-2c0-0.276 0.224-0.5 0.5-0.5h16c0.276 0 0.5 0.224 0.5 0.5v2z" fill="#878c92"></path> </svg> <span>' + property.size + '</span>' +
                        '<h5 class="listing-street-address marB0"><a onclick="ct_load_single_listing(event)" class="listing-link" rel="'+property.listingID+'" href="' + property.permalink + '">' + property.title + '</a></h5>' +
                        '<p class="location muted marB10">' + property.city + ', ' + property.state + '&nbsp;' + property.zip + '</p>' +
                    '</header>' +
                '</div>';

        } else if (property['land'] == 'land') {

            return '<div class="infobox ' + svgPriceClass + '">' +
                '<div class="info-image"' +
                    '<figure>' +
                        '<a onclick="ct_load_single_listing(event)" class="listing-link" rel="'+property.listingID+'" href="' + property.permalink + '">' +
                            property.thumb +
                        '</a>' +
                    '</figure>' +
                '</div>' +
                '<div class="listing-details">' +
                    '<header>' +
                        '<h4 class="marT5 marB5 price"><a onclick="ct_load_single_listing(event)" class="listing-link" rel="'+property.listingID+'" href="' + property.permalink + '">' + property.fullPrice + '</a></h4>' +
                        '<p class="listing-icons muted marB0">' + '<svg id="ico-size" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="14" height="14" viewBox="0 0 20 20"> <path d="M17.5 3h-8.5v-0.5c0-0.827-0.673-1.5-1.5-1.5h-2c-0.827 0-1.5 0.673-1.5 1.5v0.5h-2.5c-0.827 0-1.5 0.673-1.5 1.5v2c0 0.827 0.673 1.5 1.5 1.5h2.5v10.5c0 0.827 0.673 1.5 1.5 1.5h2c0.827 0 1.5-0.673 1.5-1.5v-10.5h8.5c0.827 0 1.5-0.673 1.5-1.5v-2c0-0.827-0.673-1.5-1.5-1.5zM5 2.5c0-0.276 0.224-0.5 0.5-0.5h2c0.276 0 0.5 0.224 0.5 0.5v0.5h-3v-0.5zM8 10h-0.5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h0.5v2h-0.5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h0.5v2h-0.5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h0.5v1.5c0 0.276-0.224 0.5-0.5 0.5h-2c-0.276 0-0.5-0.224-0.5-0.5v-10.5h3v2zM18 6.5c0 0.276-0.224 0.5-0.5 0.5h-1.5v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-2v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-2v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-2v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-2v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-1.5c-0.276 0-0.5-0.224-0.5-0.5v-2c0-0.276 0.224-0.5 0.5-0.5h16c0.276 0 0.5 0.224 0.5 0.5v2z" fill="#878c92"></path> </svg> <span>' + property.lotsize + '</span></p>' +
                        '<h5 class="listing-street-address marB0"><a onclick="ct_load_single_listing(event)" class="listing-link" rel="'+property.listingID+'" href="' + property.permalink + '">' + property.title + '</a></h5>' +
                        '<p class="location muted marB10">' + property.city + ', ' + property.state + '&nbsp;' + property.zip + '</p>' +
                    '</header>' +
                '</div>';

        } else {

            return '<div class="infobox ' + svgPriceClass + '">' +
                '<div class="info-image"' +
                    '<figure>' +
                        '<a onclick="ct_load_single_listing(event)" class="listing-link" rel="'+property.listingID+'" href="' + property.permalink + '">' +
                            property.thumb +
                        '</a>' +
                    '</figure>' +
                '</div>' +
                '<div class="listing-details">' +
                    '<header>' +
                        '<h4 class="marT5 marB5 price"><a onclick="ct_load_single_listing(event)" class="listing-link" rel="'+property.listingID+'" href="' + property.permalink + '">' + property.fullPrice + '</a></h4>' +
                        '<p class="listing-icons muted marB0">' + '<svg id="ico-bed" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="14" height="14" viewBox="0 0 20 20"> <path d="M17.5 18h-1c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h1c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#878c92"></path> <path d="M2.5 18h-1c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h1c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#878c92"></path> <path d="M18.658 11.393l-2.368-7.103c-0.199-0.596-0.768-1.086-1.388-1.24-0.022-0.095-0.046-0.186-0.074-0.27-0.227-0.68-0.616-0.781-0.828-0.781h-4c-0.127 0-0.318 0.037-0.5 0.213-0.182-0.176-0.373-0.213-0.5-0.213h-4c-0.212 0-0.601 0.102-0.828 0.781-0.028 0.084-0.053 0.174-0.074 0.27-0.621 0.154-1.19 0.643-1.388 1.24l-2.368 7.103c-0.192 0.575-0.342 1.501-0.342 2.107v2c0 0.827 0.673 1.5 1.5 1.5h16c0.827 0 1.5-0.673 1.5-1.5v-2c0-0.606-0.15-1.532-0.342-2.107zM10.157 3h3.686c0.070 0.157 0.157 0.514 0.157 1s-0.087 0.843-0.157 1h-3.686c-0.070-0.157-0.157-0.514-0.157-1s0.087-0.843 0.157-1zM5.157 3h3.686c0.070 0.157 0.157 0.514 0.157 1s-0.087 0.843-0.157 1h-3.686c-0.070-0.157-0.157-0.514-0.157-1s0.087-0.843 0.157-1zM3.658 4.607c0.054-0.162 0.185-0.317 0.345-0.429 0.014 0.388 0.072 0.752 0.169 1.041 0.227 0.68 0.616 0.781 0.828 0.781h4c0.127 0 0.318-0.037 0.5-0.213 0.182 0.176 0.373 0.213 0.5 0.213h4c0.212 0 0.601-0.102 0.828-0.781 0.096-0.289 0.155-0.654 0.169-1.041 0.16 0.113 0.291 0.267 0.345 0.429l0.798 2.393h-13.279l0.798-2.393zM2.527 8h13.946l1.236 3.709c0.032 0.095 0.062 0.204 0.091 0.321-0.097-0.020-0.197-0.030-0.3-0.030h-16c-0.103 0-0.203 0.010-0.3 0.030 0.029-0.117 0.059-0.226 0.091-0.321l1.237-3.709zM18 15.5c0 0.276-0.224 0.5-0.5 0.5h-16c-0.276 0-0.5-0.224-0.5-0.5v-2c0-0.276 0.224-0.5 0.5-0.5h16c0.276 0 0.5 0.224 0.5 0.5v2z" fill="#878c92"></path> </svg> <span>' + property.bed + '</span>' +
                        '<svg id="ico-bath" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="14" height="14" viewBox="0 0 20 20"> <path d="M16.5 20h-1c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h1c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#878c92"></path> <path d="M4.5 20h-1c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h1c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#878c92"></path> <path d="M20 12.5c0-0.827-0.673-1.5-1.5-1.5h-15.5v-9.5c0-0.276 0.224-0.5 0.5-0.5h1.5c0.142 0 0.399 0.106 0.5 0.207l0.126 0.126c-0.929 1.27-0.821 3.068 0.326 4.215 0.094 0.094 0.221 0.146 0.354 0.146s0.26-0.053 0.354-0.146l3.889-3.889c0.195-0.195 0.195-0.512 0-0.707-0.614-0.614-1.43-0.952-2.298-0.952-0.699 0-1.364 0.219-1.918 0.625l-0.125-0.125c-0.29-0.29-0.797-0.5-1.207-0.5h-1.5c-0.827 0-1.5 0.673-1.5 1.5v9.5h-0.5c-0.827 0-1.5 0.673-1.5 1.5 0 0.652 0.418 1.208 1 1.414v2.586c0 1.378 1.122 2.5 2.5 2.5h13c1.378 0 2.5-1.122 2.5-2.5v-2.586c0.582-0.206 1-0.762 1-1.414zM9.448 1.345l-3.103 3.103c-0.546-0.869-0.442-2.033 0.314-2.789 0.425-0.425 0.99-0.659 1.591-0.659 0.431 0 0.843 0.12 1.198 0.345zM16.5 18h-13c-0.827 0-1.5-0.673-1.5-1.5v-2.5h16v2.5c0 0.827-0.673 1.5-1.5 1.5zM18.5 13h-17c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h17c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#878c92"></path> <path d="M10 6.5c0 0.276-0.224 0.5-0.5 0.5s-0.5-0.224-0.5-0.5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5z" fill="#878c92"></path> <path d="M10 4.5c0 0.276-0.224 0.5-0.5 0.5s-0.5-0.224-0.5-0.5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5z" fill="#878c92"></path> <path d="M12 4.5c0 0.276-0.224 0.5-0.5 0.5s-0.5-0.224-0.5-0.5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5z" fill="#878c92"></path> <path d="M12 6.5c0 0.276-0.224 0.5-0.5 0.5s-0.5-0.224-0.5-0.5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5z" fill="#878c92"></path> <path d="M14 4.5c0 0.276-0.224 0.5-0.5 0.5s-0.5-0.224-0.5-0.5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5z" fill="#878c92"></path> <path d="M10 8.5c0 0.276-0.224 0.5-0.5 0.5s-0.5-0.224-0.5-0.5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5z" fill="#878c92"></path> <path d="M14 6.5c0 0.276-0.224 0.5-0.5 0.5s-0.5-0.224-0.5-0.5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5z" fill="#878c92"></path> <path d="M12 8.5c0 0.276-0.224 0.5-0.5 0.5s-0.5-0.224-0.5-0.5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5z" fill="#878c92"></path> </svg><span>' + property.bath + '</span>' +
                        '<svg id="ico-size" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="14" height="14" viewBox="0 0 20 20"> <path d="M17.5 3h-8.5v-0.5c0-0.827-0.673-1.5-1.5-1.5h-2c-0.827 0-1.5 0.673-1.5 1.5v0.5h-2.5c-0.827 0-1.5 0.673-1.5 1.5v2c0 0.827 0.673 1.5 1.5 1.5h2.5v10.5c0 0.827 0.673 1.5 1.5 1.5h2c0.827 0 1.5-0.673 1.5-1.5v-10.5h8.5c0.827 0 1.5-0.673 1.5-1.5v-2c0-0.827-0.673-1.5-1.5-1.5zM5 2.5c0-0.276 0.224-0.5 0.5-0.5h2c0.276 0 0.5 0.224 0.5 0.5v0.5h-3v-0.5zM8 10h-0.5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h0.5v2h-0.5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h0.5v2h-0.5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h0.5v1.5c0 0.276-0.224 0.5-0.5 0.5h-2c-0.276 0-0.5-0.224-0.5-0.5v-10.5h3v2zM18 6.5c0 0.276-0.224 0.5-0.5 0.5h-1.5v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-2v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-2v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-2v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-2v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-1.5c-0.276 0-0.5-0.224-0.5-0.5v-2c0-0.276 0.224-0.5 0.5-0.5h16c0.276 0 0.5 0.224 0.5 0.5v2z" fill="#878c92"></path> </svg> <span>' + property.size + '</span></p>' +
                        '<h5 class="listing-street-address marB0"><a onclick="ct_load_single_listing(event)" class="listing-link" rel="'+property.listingID+'" href="' + property.permalink + '">' + property.title + '</a></h5>' +
                        '<p class="location muted marB10">' + property.city + ', ' + property.state + '&nbsp;' + property.zip + '</p>' +
                    '</header>' +
                '</div>';

        }

    }

}

function init_canvas_projection(map) {
    function CanvasProjectionOverlay() {
    }

    CanvasProjectionOverlay.prototype = new google.maps.OverlayView();
    CanvasProjectionOverlay.prototype.constructor = CanvasProjectionOverlay;
    CanvasProjectionOverlay.prototype.onAdd = function () {
    };
    CanvasProjectionOverlay.prototype.draw = function () {
    };
    CanvasProjectionOverlay.prototype.onRemove = function () {
    };

    canvasProjectionOverlay = new CanvasProjectionOverlay();
    canvasProjectionOverlay.setMap(map);
}

function convert_offset(latlng, x_offset, y_offset) {
    var proj = canvasProjectionOverlay.getProjection();
    var point = proj.fromLatLngToContainerPixel(latlng);
    point.x = point.x + x_offset;
    point.y = point.y + y_offset;
    return proj.fromContainerPixelToLatLng(point);
}

function getMapPin(property) {

    property.ctStatus = property.ctStatus.trim();

    if (mapping_ajax_object.listing_marker_type == 'svg') {
        svgSize = mapping_ajax_object.ct_listing_marker_svg_size;
        svgWidth = svgSize * 2;
        svgHeight = svgSize * 2;
        enableMarkerPrice = mapping_ajax_object.ct_enable_marker_price;
        priceZoomLevel = parseInt(mapping_ajax_object.search_marker_price_zoom_level);
        svgVersion = '?ver=1.0.7';

        if (property.mapZoom == undefined) {
            property.mapZoom = map.getZoom();
        }

        if (showMarkerWithPrice(property)) {
            svgWidth = 40;
            svgHeight = 60;
            labelOriginY = 22;
            property.priceDisplay = 'true';

            //default SVG icon with price holder
            svgURL = property["siteURL"] + '/images/svgs/map-marker-price.svg';

            // For Sale SVG icon with price holder.
            if (property.ctStatus == 'for-sale') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-price-for-sale.svg';
            }

            // Active SVG icon with price holder
            if (property.ctStatus == 'active') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-price-for-sale.svg';
            }

            // For Rent SVG icon with price holder
            if (property.ctStatus == 'for-rent') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-price-for-rent-rental.svg';
            }

            // Rental SVG icon with price holder
            if (property.ctStatus == 'rental') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-price-for-rent-rental.svg';
            }

            // REO Bank Owned SVG icon with price holder
            if (property.ctStatus == 'reo-bank-owned') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-price-reo-bank-owned.svg';
            }

            // Short Sale SVG icon with price holder
            if (property.ctStatus == 'short-sale') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-price-reduced-short-sale.svg';
            }

            // Leased SVG icon with price holder
            if (property.ctStatus == 'leased') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-price-leased-rented.svg';
            }

            // Rented SVG icon with price holder
            if (property.ctStatus == 'rented') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-price-leased-rented.svg';
            }

            // Reduced SVG icon with price holder
            if (property.ctStatus == 'reduced') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-price-reduced-short-sale.svg';
            }

            // Sold SVG icon with price holder
            if (property.ctStatus == 'sold') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-price-sold.svg';
            }

            // Pending SVG icon with price holder
            if (property.ctStatus == 'pending') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-price-pending.svg';
            }

            // Open House SVG icon with price holder
            if (property.ctStatus == 'open-house') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-price-open-house.svg';
            }

            // Available SVG icon with price holder
            if (property.ctStatus == 'available') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-price-available.svg';
            }

            // New Addition SVG icon with price holder
            if (property.ctStatus == 'new-addition') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-price-new-addition.svg';
            }

            // New Listing SVG icon with price holder
            if (property.ctStatus == 'new-listing') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-price-new-addition.svg';
            }

            // Special Offer SVG icon with price holder
            if (property.ctStatus == 'special-offer') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-price-for-sale.svg';
            }

        } else {

            labelOriginY = 10;
            property.priceDisplay = 'false';
            svgWidth = svgSize * 3;
            svgHeight = svgSize * 3;

            //default SVG icon.
            svgURL = property["siteURL"] + '/images/svgs/map-marker.svg';
            // For Sale SVG icon.
            if (property.ctStatus == 'for-sale') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-for-sale.svg';
            }

            // Active SVG icon
            if (property.ctStatus == 'active') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-for-sale.svg';
            }

            // For Rent SVG icon
            if (property.ctStatus == 'for-rent') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-for-rent-rental.svg';
            }

            // Rental SVG icon
            if (property.ctStatus == 'rental') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-for-rent-rental.svg';
            }

            // REO Bank Owned SVG icon
            if (property.ctStatus == 'reo-bank-owned') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-reo-bank-owned.svg';
            }

            // Short Sale SVG icon
            if (property.ctStatus == 'short-sale') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-reduced-short-sale.svg';
            }

            // Leased SVG icon
            if (property.ctStatus == 'leased') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-leased-rented.svg';
            }

            // Rented SVG icon with price holder.
            if (property.ctStatus == 'rented') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-leased-rented.svg';
            }

            // Reduced SVG icon
            if (property.ctStatus == 'reduced') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-reduced-short-sale.svg';
            }

            // Sold SVG icon
            if (property.ctStatus == 'sold') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-sold.svg';
            }

            // Pending SVG icon
            if (property.ctStatus == 'pending') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-pending.svg';
            }

            // Open House SVG icon
            if (property.ctStatus == 'open-house') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-open-house.svg';
            }

            // Available SVG icon
            if (property.ctStatus == 'available') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-available.svg';
            }

            // New Addition SVG icon
            if (property.ctStatus == 'new-addition') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-new-addition.svg';
            }

            // New Listing SVG icon
            if (property.ctStatus == 'new-listing') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-new-addition.svg';
            }

            // Special Offer SVG icon
            if (property.ctStatus == 'special-offer') {
                svgURL = property["siteURL"] + '/images/svgs/map-marker-for-sale.svg';
            }

        }

        svgURL = svgURL + '#' + property.listingID + svgVersion;

        return {
            url: svgURL,
            size: new google.maps.Size(svgWidth, svgHeight),
            scaledSize: new google.maps.Size(svgWidth, svgHeight),
            labelOrigin: new google.maps.Point(20, labelOriginY),
        }

    } else {

        if (property['commercial'] == 'commercial') {
            return {
                url: property["siteURL"] + '/images/map-pin-com.png' + '#' + property.listingID,
                size: new google.maps.Size(40, 46),
                scaledSize: new google.maps.Size(40, 46)
            };
        } else if (property['land'] == 'land' || property['land'] == 'lot') {
            return {
                url: property["siteURL"] + '/images/map-pin-land.png' + '#' + property.listingID,
                size: new google.maps.Size(40, 46),
                scaledSize: new google.maps.Size(40, 46)
            };
        }

        return {
            url: property["siteURL"] + '/images/map-pin-res.png' + '#' + property.listingID,
            size: new google.maps.Size(40, 46),
            scaledSize: new google.maps.Size(40, 46)
        }
    }


}

/*****************************************************
 *
 * Cancel Drawing  FreeHand Area in the Map
 *
 ******************************************************/

function cancelDrawing() {

    jQuery('#map-wrap .drawInstructionBar').remove();
    jQuery('#ct-map-navigation, #compare-panel').show();
    jQuery('#map .gm-style > div > div > div > div > img').show();
    jQuery('#map > div > div > div:nth-child(1) > div:nth-child(1) > div:nth-child(4)').show();
    jQuery('#map > div > div > div:nth-child(1) > div:nth-child(3)').css('opacity', '1');
    drawBounds = null;

}

/*****************************************************
 *
 * Add offset to markers that has the same positions.
 * @uses marker_list
 * @author Joseph G.
 *
 ******************************************************/

function map_markers_same_loc_reposition() {

    if ( ! marker_list ) {
        return;
    }

    // Iterate on each market.
    marker_list.forEach( (marker) => {

        var has_same_location = false;
        // Get the current marker's latitude.
        var marker_lat = marker.getPosition().lat();
        // Get the current marker's longitude.
        var marker_lng  = marker.getPosition().lng();

        // Check in the list of marker to see if the position exists.
        for( i = 0; i < marker_list.length ; i++){

            var e_loc_lat = marker_list[i].getPosition().lat();
            var e_loc_lng = marker_list[i].getPosition().lng();

            if ( e_loc_lat && e_loc_lng && marker_lat && marker_lng ) {

                if ( marker_lat === e_loc_lat && marker_lng === e_loc_lng ) {

                    // Skip self-check.
                    if ( marker.listingID === marker_list[i].listingID ) {
                        return;
                    }
                    var j = i + 20;
                    var angle = 0.75 * j;

                    var a =  0.05;
                    var b =  0.05;
                    var x = (a + b * angle) * Math.cos(angle);
                    var y = (a + b * angle) * Math.sin(angle);

                    var lat = marker.getPosition().lat() + x/15000;
                    var lng = marker.getPosition().lng() + y/15000;

                    var latLng = new google.maps.LatLng(lat, lng);

                    marker_list[i].setPosition(latLng);

                    has_same_location = true;

                }
           }
        } // End for.

    });
}

/*****************************************************
 *
 * Draw FreeHand Area in the Map
 *
 ******************************************************/

function drawFreeHand() {

    poly = new google.maps.Polyline({map: map, clickable: false});
    var move = google.maps.event.addListener(map, 'mousemove', function (e) {
        poly.getPath().push(e.latLng);
    });

    // Mouse Up Listener
    var map_mouse_up_listener = google.maps.event.addListenerOnce(map, 'mouseup', function (e) {

        google.maps.event.removeListener(move);
        google.maps.event.removeListener(map_mouse_up_listener);
        var path = poly.getPath();
        poly.setMap(null);
        poly = new google.maps.Polygon({
            map: map,
            path: path,
            strokeOpacity: 0.3,
            fillOpacity: 0.15,
            strokeColor: '#8e9092',
            strokeWeight: 1,
        });

        google.maps.event.clearListeners(map.getDiv(), 'mousedown');
        getMarkersInsideDraw(e, poly);

        if (drawMarkers.length > 0) {
            oldZoom = map.getZoom();
            oldBounds = map.getBounds();
            jQuery("#number-listings-progress").css("display", "block");
            jQuery('#map > div > div > div:nth-child(1) > div:nth-child(3)').css('opacity', '0');
            let order_by = jQuery("select#ct_orderby").val();
            doAjax('', '', 1, () => {}, {ct_orderby: order_by });
            jQuery('#map-wrap .drawInstructionBar').remove();
            jQuery('#ct-map-navigation, #compare-panel').show();
        } else {
            cancelDrawing();
            disableDrawMode();
        }

    });
}


/*****************************************************
 *
 * Enable Draw Mode
 *
 ******************************************************/

function disableDrawMode() {

    map.setOptions({
        draggable: true,
        draggableCursor: 'grab',
        zoomControl: true,
        scrollwheel: true,
        disableDoubleClickZoom: true
    });

    jQuery('#map .gm-style > div > div > div > div > img').show();
    jQuery('#map > div > div > div:nth-child(1) > div:nth-child(1) > div:nth-child(4)').show();
    jQuery('#map > div > div > div:nth-child(1) > div:nth-child(3)').css('opacity', '1');

    drawingMode = false;
}

/*****************************************************
 *
 * Disable Draw Mode
 *
 ******************************************************/

function enableDrawMode() {

    jQuery('#map .gm-style > div > div > div > div > img').hide();
    jQuery('#map > div > div > div:nth-child(1) > div:nth-child(1) > div:nth-child(4)').hide();
    jQuery('#map > div > div > div:nth-child(1) > div:nth-child(3)').css('opacity', '0');

    drawingMode = true;

    map.setOptions({
        draggable: false,
        //draggableCursor : "url(http://s3.amazonaws.com/besport.com_images/status-pin.png), auto",
        draggableCursor: 'crosshair',
        zoomControl: false,
        scrollwheel: false,
        disableDoubleClickZoom: false,
        clickableIcons: false,
    });
}

/*****************************************************
 *
 * Get list of Markers inside the Draw Area
 *
 ******************************************************/
 
function getMarkersInsideDraw(e, polygon) {
    drawMarkers = [];
    drawBounds = new google.maps.LatLngBounds();

    for (var i = 0; i < marker_list.length; i++) {
        if (google.maps.geometry.poly.containsLocation(marker_list[i].position, polygon)) {
            if (marker_list[i].listingID != undefined) {
                drawMarkers.push(Number(marker_list[i].listingID));
                drawBounds.extend(marker_list[i].position);
            }
        }
    }

}

function showMarkerWithPrice(property) {

    markerType = mapping_ajax_object.listing_marker_type;
    enableMarkerPrice = mapping_ajax_object.ct_enable_marker_price;
    priceZoomLevel = parseInt(mapping_ajax_object.search_marker_price_zoom_level);

    if (property.mapZoom == undefined) {
        property.mapZoom = map.getZoom();
    }

    if (priceZoomLevel <= property.mapZoom && enableMarkerPrice == 'yes' && mapping_ajax_object.listing_marker_type == 'svg') {
        return true;
    }
}

/*****************************************************
 *
 * End Map Draw/Pan/Zoom/Scroll Update Markers
 *
 ******************************************************/

var estateMapping = (function () {

    var markers_latlng_collection = [];
    var self = {},
        current_marker = 0,
        x_center_offset = 0, // x,y offset in px when map gets built with marker bounds
        y_center_offset = 0;

    /**
     * Convert Sorting to Ajax.
     */
    jQuery(document).ready(function($){

        /**
         * Attach the change event to select orderby.
         */
        $('#listings-results').on("change", "select#ct_orderby", function(e) {

            "use strict";

            // Check if the map is loaded or not.
            if ( ! self.map ) {
                // Use URLSearchParams to construct new object to pass the 'ct_orderby' parameter.  
                const url_parameters = new URLSearchParams( window.location.search );
                url_parameters.set('ct_orderby', $(this).val());
                // Redirect with our new parameter.
                window.location.search = url_parameters;
                return;
            }

            // Always reset the page to 1 when resetting the options.
            let page = 1;
            // Get the north-east map bounds.
            let ne = self.map.getBounds().getNorthEast();
            // Get the south-west map bounds.
            let sw = self.map.getBounds().getSouthWest();
            // Get the actual order by value.
            let order_by = $(this).val();

            // Only execute ajax when order_by is not empty.
            if ( typeof order_by !== null && order_by.length >= 1) {
                // Show some mock preloader.
                $('#search-listing-mapper li.listing').css({
                    'opacity': 0.5,
                    'pointer-events': 'none'
                });

                doAjax(ne, sw, page, ()=>{}, { ct_orderby: order_by.trim() });
            }

        });
    });


    /**
     * Convert Pagination to Ajax
     */
    jQuery(document).ready(do_ajax_paging);

    function do_ajax_paging($) {

        let current_page = parseInt($('.pagination ul > li.current > a').text());

        $('body.search-listings').on('click', '#search-listing-mapper .pagination li a', function (e) {

            // Bail out if no maps are defined
            if (!self.map) {
                return;
            }

            e.preventDefault();

            let ne = self.map.getBounds().getNorthEast();
            let sw = self.map.getBounds().getSouthWest();
            let page = parseInt($(this).text());
            let parent_li_id = $(this).parent().attr('id');
            let $li = $('#search-listing-mapper .pagination li');

            // Read the sorting method.
            let order_by = $("select#ct_orderby").val();

            // Update the current page.
            current_page = parseInt($('.pagination ul > li.current > a').text());

            // Show some mock preloader.
            $('#search-listing-mapper li.listing').css({
                'opacity': 0.5,
                'pointer-events': 'none'
            });

            // Next/Previous is NaN so we handle it properly
            if (isNaN(page)) {

                page = current_page;

                if ('next-page-link' === parent_li_id) {
                    page++;
                    $('.pagination ul > li.current').removeClass('current').next().addClass('current');
                }
                if ('prev-page-link' === parent_li_id) {
                    page--;
                    $('.pagination ul > li.current').removeClass('current').prev().addClass('current');
                }

            } else {
                // Put some active states in dom
                $li.removeClass('current');
                $(this).parent().addClass('current');
            }

            // Disable pagination. Make sure the client navigate the page one by one
            $('.pagination').css({
                'pointer-events': 'none'
            });

            // Scroll to top for side-results
            if ($('.side-results').html()) {
                // If layout is set to "side by side"
                if ( $("#main-content").attr("data-layout") === "grid" ) {
                    // Scroll to the top of the search listing mapper position when grid layout is selected
                    // If on mobile use "Save Search" element to scroll results to top
                    // Since ".side-results" is not scrollable div anymore
                    if(navigator.userAgent.match(/(iPod|iPhone|iPad|Android)/)) {           
                        document.querySelector("#searched-save-search")
                          .scrollIntoView({block: "start", behavior: "smooth"})

                    } else {
                        $('html, body').animate({scrollTop: $('#search-listing-mapper').position().top - 70}, 'slow');
                    }
                }
                if(navigator.userAgent.match(/(iPod|iPhone|iPad|Android)/)) {           
                    document.querySelector("#searched-save-search")
                          .scrollIntoView({block: "start", behavior: "smooth"})
                } else {
                    $('.side-results').animate({scrollTop: 0}, 'slow');
                }
            } else {
                // Scroll to listing mapper top position instead
                // If on mobile use "Save Search" element to scroll results to top
                if(navigator.userAgent.match(/(iPod|iPhone|iPad|Android)/)) {           
                    document.querySelector("#searched-save-search")
                          .scrollIntoView({block: "start", behavior: "smooth"})
                } else {
                    $('html, body').animate({scrollTop: $('#search-listing-mapper').position().top}, 'slow');
                }
            }

            // Do some ajax
            doAjax(ne, sw, page, ()=>{}, {ct_orderby: order_by });

        });
    }

    function build_marker(latlng, property, skipBounds = false) {

        var mapPin = getMapPin(property);
        //priceZoomLevel    = parseInt( mapping_ajax_object.search_marker_price_zoom_level );
        //enableMarkerPrice = mapping_ajax_object.ct_enable_marker_price;

        if (property.mapZoom == undefined) {
            property.mapZoom = map.getZoom();
        }

        priceLabel = '';

        if (mapping_ajax_object.listing_marker_type == 'svg') {
            mapAnimation = null;
        } else {
            mapAnimation = google.maps.Animation.DROP;
        }

        //if ( priceZoomLevel <= map.getZoom() && enableMarkerPrice == 'yes' && mapping_ajax_object.listing_marker_type == 'svg' ) {
        if (showMarkerWithPrice(property)) {
            priceLabel = {
                text: property.markerPrice,
                color: '#222',
                fontSize: '11px',
                fontWeight: 'normal'
            };
        }

        var marker = new google.maps.Marker({
            map: self.map,
            animation: mapAnimation,
            draggable: false,
            flat: true,
            fullScreenControl: true,
            fullscreenControlOptions: {
                position: google.maps.ControlPosition.BOTTOM_LEFT
            },
            icon: mapPin,
            position: latlng,
            listingID: property.listingID,
            label: priceLabel,
            iconURL: mapPin.url,
            ctStatus: property.ctStatus,
            priceDisplay: property.priceDisplay,
        });

        var residentialString = '';
        if (property['commercial'] != 'commercial') {
            var residentialString = '' + property.bed + ' | ' + property.bath + ' | ';
        }

        var contentString = getInfoBoxContentString(property);

        var imagesURL = property.siteURL;

        if (mapping_ajax_object.listing_marker_type == 'svg') {
            if (priceZoomLevel <= map.getZoom() && enableMarkerPrice == 'yes') {
                pixOff = new google.maps.Size(-125, (mapping_ajax_object.ct_listing_marker_svg_size * -1) - 50);
            } else {
                pixOff = new google.maps.Size(-125, (mapping_ajax_object.ct_listing_marker_svg_size * -1) - 30);
            }

        } else {
            pixOff = new google.maps.Size(-125, -64);
        }

        var infobox = new InfoBox({
            content: contentString,
            disableAutoPan: true,
            maxWidth: 0,
            alignBottom: true,
            pixelOffset: pixOff,
            zIndex: null,
            closeBoxMargin: "8px 0 -20px -20px",
            closeBoxURL: imagesURL + '/images/infobox-close.png',
            infoBoxClearance: new google.maps.Size(1, 1),
            isHidden: false,
            pane: "floatPane",
            enableEventPropagation: false
        });

        marker.infobox = infobox;
        google.maps.event.addListener(marker, 'click', function () {

            if (open_info_window) open_info_window.close();

            infobox.open(self.map, marker);
            self.map.panTo(convert_offset(this.position, x_info_offset, y_info_offset));
            open_info_window = infobox;

        });

        marker_list.push(marker);

        if (skipBounds == false) {
            self.bounds.extend(latlng);
            self.map.fitBounds(self.bounds);
        }

        // REMOVE is the below call needed?
        //self.map.setCenter(convert_offset(self.bounds.getCenter(), x_center_offset, y_center_offset));


    }

    // Next/Previous Marker Navigation
    var ct_map_next = function () {
        current_marker++;
        if (current_marker > marker_list.length) {
            current_marker = 1;
        }
        while (marker_list[current_marker - 1].visible === false) {
            current_marker++;
            if (current_marker > marker_list.length) {
                current_marker = 1;
            }
        }
        google.maps.event.trigger(marker_list[current_marker - 1], 'click');
    }

    var ct_map_prev = function () {
        current_marker--;
        if (current_marker < 1) {
            current_marker = marker_list.length;
        }
        while (marker_list[current_marker - 1].visible === false) {
            current_marker--;
            if (current_marker > marker_list.length) {
                current_marker = 1;
            }
        }
        google.maps.event.trigger(marker_list[current_marker - 1], 'click');
    }

    /**
     * Draw, Next, Prev buttons
     */
    jQuery(document).on('ready', function () {
        
        var ctGmapNext = document.getElementById('ct-gmap-next');
        var ctGmapPrev = document.getElementById('ct-gmap-prev');
        var ctGmapDraw = document.getElementById('ct-gmap-draw');

        if (ctGmapDraw != null) {
            document.getElementById('ct-gmap-draw').addEventListener('click', function () {

                if (poly != null) {
                    poly.setMap(null);
                    poly = null;
                    self.map.fitBounds(oldBounds);
                    self.map.setZoom(oldZoom);
                    google.maps.event.trigger(self.map, 'zoom_changed');
                    //getMapBounds(map);
                    cancelDrawing();
                    disableDrawMode();
                    jQuery('.draw-mode').find('span').html('');
                    jQuery('.draw-mode').removeClass('draw-mode');
                    drawMarkers = [];

                    return false;

                } else {

                    jQuery('#ct-map-navigation, #compare-panel').hide();
                    jQuery('#map-wrap').append('<div class="drawInstructionBar"><div class="instructions">' + mapping_ajax_object.click_drag_draw + '<a href="#" class="btn btn-sm cancelDrawButton" data-rf-test-id="cancelDrawButton">' + mapping_ajax_object.cancel_draw + '</a></div></div>');
                    jQuery(this).addClass('draw-mode');
                    jQuery(this).find('span').html( mapping_ajax_object.clear_draw );
                    enableDrawMode();
                }

                drawListener = google.maps.event.addDomListener(map.getDiv(), 'mousedown', function (e) {
                    if (drawingMode) {
                        drawFreeHand();
                    } else {
                        disableDrawMode();
                    }
                });
                return false;
            });
        }

        if (ctGmapNext != null) {
            document.getElementById('ct-gmap-next').addEventListener('click', function () {
                ct_map_next();
            });
        }

        if (ctGmapPrev != null) {
            document.getElementById('ct-gmap-prev').addEventListener('click', function () {
                ct_map_prev();
            });
        }
    });

    function geocode_and_place_marker(property, skipBounds = false) {

        var geocoder = new google.maps.Geocoder();
        var address = property.street + ', ' + property.city + ' ' + property.state + ', ' + property.zip;


        //If latlong exists build the marker, otherwise geocode then build the marker
        if (property['latlong'] && property['latlong'].length > 1) {

            var lat = parseFloat(property['latlong'].split(',')[0]),
                lng = parseFloat(property['latlong'].split(',')[1]);

            var latlng = new google.maps.LatLng(lat, lng);

                build_marker(latlng, property, skipBounds);

        } else {
            console.warn("Latitude and longitude is not available for listing id: " + property.listingID );
            console.warn("Geocoding address: " + address );

            geocoder.geocode({address: address}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    console.info("Successfully geocoded the address: " + address + " " + results[0].geometry.location );
                    var latlng = results[0].geometry.location;
                        build_marker(latlng, property, skipBounds);
                }
            });
        }
    }

    self.init_property_map = function (properties, defaultmapcenter, siteURL) {

        var ctMapType = ctMapGlobal['mapType'];
        var ctMapStyle = ctMapGlobal['mapStyle'];
        var ctMapCustomStyles = ctMapGlobal['mapCustomStyles'];

        if (ctMapStyle == 'custom') {

            if (defaultmapcenter != null && defaultmapcenter.mapcenter != '') {

                var options = {
                    zoom: 10,
                    center: new google.maps.LatLng(defaultmapcenter.mapcenter),
                    mapTypeId: google.maps.MapTypeId[ctMapType],
                    disableDefaultUI: false,
                    scrollwheel: false,
                    streetViewControl: false,
                    styles: [{
                        "featureType": "water",
                        "stylers": [{"visibility": "on"}, {"color": "#acbcc9"}]
                    }, {"featureType": "landscape", "stylers": [{"color": "#f2e5d4"}]}, {
                        "featureType": "road.highway",
                        "elementType": "geometry",
                        "stylers": [{"color": "#c5c6c6"}]
                    }, {
                        "featureType": "road.arterial",
                        "elementType": "geometry",
                        "stylers": [{"color": "#e4d7c6"}]
                    }, {
                        "featureType": "road.local",
                        "elementType": "geometry",
                        "stylers": [{"color": "#fbfaf7"}]
                    }, {
                        "featureType": "poi.park",
                        "elementType": "geometry",
                        "stylers": [{"color": "#c5dac6"}]
                    }, {
                        "featureType": "administrative",
                        "stylers": [{"visibility": "on"}, {"lightness": 33}]
                    }, {"featureType": "road"}, {
                        "featureType": "poi.park",
                        "elementType": "labels",
                        "stylers": [{"visibility": "on"}, {"lightness": 20}]
                    }, {}, {"featureType": "road", "stylers": [{"lightness": 20}]}]
                };
            } else {

                var options = {
                    mapTypeId: google.maps.MapTypeId[ctMapType],
                    zoom: 10,
                    disableDefaultUI: false,
                    scrollwheel: false,
                    streetViewControl: false,
                    styles: [{
                        "featureType": "water",
                        "stylers": [{"visibility": "on"}, {"color": "#acbcc9"}]
                    }, {"featureType": "landscape", "stylers": [{"color": "#f2e5d4"}]}, {
                        "featureType": "road.highway",
                        "elementType": "geometry",
                        "stylers": [{"color": "#c5c6c6"}]
                    }, {
                        "featureType": "road.arterial",
                        "elementType": "geometry",
                        "stylers": [{"color": "#e4d7c6"}]
                    }, {
                        "featureType": "road.local",
                        "elementType": "geometry",
                        "stylers": [{"color": "#fbfaf7"}]
                    }, {
                        "featureType": "poi.park",
                        "elementType": "geometry",
                        "stylers": [{"color": "#c5dac6"}]
                    }, {
                        "featureType": "administrative",
                        "stylers": [{"visibility": "on"}, {"lightness": 33}]
                    }, {"featureType": "road"}, {
                        "featureType": "poi.park",
                        "elementType": "labels",
                        "stylers": [{"visibility": "on"}, {"lightness": 20}]
                    }, {}, {"featureType": "road", "stylers": [{"lightness": 20}]}]
                };
            }

        } else {

            var options = {
                zoom: 10,
                center: new google.maps.LatLng(defaultmapcenter.mapcenter),
                mapTypeId: google.maps.MapTypeId[ctMapType],
                disableDefaultUI: false,
                scrollwheel: false,
                streetViewControl: true
            };
        }

        // get user's location
        lat = getUrlParameter("lat", "");
        lng = getUrlParameter("lng", "");
        skipBounds = false;

        if (lat && lng) {
            //console.log("In lat and lng portion");
            options.center = {lat: parseFloat(lat), lng: parseFloat(lng)};
            skipBounds = true;
        }

        /* Marker Clusters */
        var markerClustererOptions = {
            ignoreHidden: true,
            maxZoom: mapping_ajax_object.search_cluster_zoom_level,
            styles: [{
                textColor: '#ffffff',
                url: siteURL + '/images/cluster-icon.png',
                height: 48,
                width: 48
            }]
        };
        /**
         * Adjust the zoom level when on user location.
         */
        if (lat && lng) {
            options.zoom = 12;
        }

        self.map = new google.maps.Map(document.getElementById('map'), options);

        map = self.map;

        self.bounds = new google.maps.LatLngBounds();

        if (lat && lng) {
            /**
             * Added the following code to trigger doAjax function when lat and lng is set.
             * This is to fix the bug when the user try to use his geolocated address.
             *
             * Here, we trigger the event once to make sure it doesn't overlap with other doAjax events.
             */
            google.maps.event.addListenerOnce(map, 'bounds_changed', function () {
                let order_by = jQuery("select#ct_orderby").val();
                doAjax(
                    map.getBounds().getNorthEast(),
                    map.getBounds().getSouthWest(), 1,
                    () => {},
                    {ct_orderby: order_by }
                );
            });
        }

        init_canvas_projection(self.map);

        //wait for idle to give time to grab the projection (for calculating offset)

        if (defaultmapcenter != null) {

            var idle_listener = google.maps.event.addListener(self.map, 'idle', function () {


                // check for no results...
                //console.log("properties.length: " + properties.length);
                //console.log("properties: ");
                //console.log(properties);

                if (properties && properties.length) {
                    mapZoom = 1;
                    for (i = 0; i < properties.length; i++) {
                        properties[i].mapZoom = mapZoom;
                        properties[i].ctStatus = properties[i].ctStatus.trim();
                        geocode_and_place_marker(properties[i], skipBounds);
                    }
                    // Reposition markers if by small offset when have the same location.
                    map_markers_same_loc_reposition();

                } else { // no results

                    var geocoder = new google.maps.Geocoder();
                    var latlng;
                    var zoomLevel = 14;

                    var address = getUrlParameter("ct_keyword", "");

                    if (typeof address !== 'string') {
                        address = '';
                    }

                    geocoder.geocode({address: address}, function (results, status) {

                        if (status == google.maps.GeocoderStatus.OK) {
                            latlng = results[0].geometry.location;

                        } else {
                            // keyword was not a recognised location
                            // try the users lat lng otherwise default to usa

                            if (lat == "") {
                                lat = storedLatitude;
                                lng = storedLongitude;
                            }

                            if (lat != "" && lng != "") {

                                latlng = new google.maps.LatLng(lat, lng);
                                zoomLevel = 8;
                            } else {
                                latlng = new google.maps.LatLng(40.338354, -97.524664);
                                zoomLevel = 4;
                            }

                        }

                        self.map.setCenter(latlng);
                        self.map.setZoom(zoomLevel);
                        //self.bounds.extend(latlng);
                        //self.map.fitBounds(self.bounds);

                    });

                }

                markerCluster = new MarkerClusterer(self.map, marker_list, markerClustererOptions);

                google.maps.event.removeListener(idle_listener);
                jQuery("#number-listings-progress").css("display", "none");

                google.maps.event.addListener(self.map, 'dragend', debounce(() => {
                    skipBounds = false;
                    //console.log("dragend");
                    getMapBounds(self.map);

                }, debounceTime));


                google.maps.event.addListener(self.map, 'zoom_changed', debounce(() => {
                    skipBounds = false;
                    //console.log("zoom_changed");
                    getMapBounds(self.map);

                }, debounceTime));

            });

            google.maps.event.addListenerOnce(map, 'tilesloaded', function () {
                //google.maps.event.trigger(map, 'resize');
            });
        }

        /*****************************************************
         *
         * Start Airbnb style map scrolling
         *
         ******************************************************/
        /*
                google.maps.event.addListener( self.map, 'dragend', debounce( ( ) => {
                    skipBounds = false;
                    //console.log("dragend");
                    getMapBounds( self.map );

                }, debounceTime ) );


                google.maps.event.addListener( self.map, 'zoom_changed', debounce( ( ) => {
                    skipBounds = false;
                    //console.log("zoom_changed");
                    getMapBounds( self.map );

                }, debounceTime ) );

        /*
                if ( skipBounds == true ) {
                    hitCount++;
                    google.maps.event.addListenerOnce( self.map, 'idle', debounce( ( ) => {

                        //console.log("idle");
                        getMapBounds( self.map );

                    }, debounceTime ) );
                }
        */
        /*****************************************************
         *
         * End Airbnb style map scrolling
         *
         ******************************************************/

    }

    return self;
}());