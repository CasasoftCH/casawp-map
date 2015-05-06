var map;
var markers = [];
var infowindow = false;
var marker;

jQuery( function () {
	"use strict";
	var $ = jQuery;

	function initialize() {
		var switzerland = new google.maps.LatLng(46.8131873,8.2242101);

		var geocoder = new google.maps.Geocoder();
		geocoder.geocode( { 'address': 'switzerland'}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				map.setCenter(results[0].geometry.location);
				map.fitBounds(results[0].geometry.viewport);
			}
		});

		var customMapType = ($('#casasync-map_map').data('map_type') == 'SATELLITE') ? (google.maps.MapTypeId.SATELLITE) : (google.maps.MapTypeId.ROADMAP);
		var mapOptions = {
			center: switzerland,
			mapTypeId: customMapType,
			mapTypeControl: false,
			mapTypeControlOptions: {
				style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
				position: google.maps.ControlPosition.BOTTOM_CENTER
			},
			panControl: false,
			zoomControl: true,
			zoomControlOptions: {
				style: google.maps.ZoomControlStyle.LARGE,
				position: google.maps.ControlPosition.TOP_RIGHT
			},
			scaleControl: false,
			streetViewControl: false,
		};
		map = new google.maps.Map(document.getElementById('casasync-map_map'), mapOptions);

		setInfoWindow();

		// set marker when map has loaded
		google.maps.event.addListenerOnce(map, 'tilesloaded', function(){
			refreshMarkers();
		});
	}
	google.maps.event.addDomListener(window, 'load', initialize);


	// Sets the map on all markers in the array.
	function setAllMap(map) {
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(map);
		}
	}

	// Removes the markers from the map, but keeps them in the array.
	function clearMarkers() {
		setAllMap(null);
	}

	// Deletes all markers in the array by removing references to them.
	function deleteMarkers() {
		clearMarkers();
		markers = [];
	}

	function addMarker(el) {
		infowindow.close(map);

		var markerImage = '';
		if (window.casasyncMapOptions && window.casasyncMapOptions.marker_image) {
			markerImage = window.casasyncMapOptions.marker_image;
		}
		marker = new google.maps.Marker({
			position: new google.maps.LatLng(el.lat, el.lng),
			map: map,
			icon: markerImage,
		});
		markers.push(marker);

		google.maps.event.addListener(marker, 'click', (function(marker) {
			renderInfoWindow(el);
		}));
	}

	function setInfoWindow() {
		var closeButton = false;
		if (window.casasyncMapOptions && window.casasyncMapOptions.plugin_url) {
			var closeButton = window.casasyncMapOptions.plugin_url + 'assets/img/close.png';
		}
		var myOptions = {
			pixelOffset: new google.maps.Size(-140, 0),
			closeBoxMargin: "18px 5px 5px 5px",
			closeBoxURL: closeButton,
			infoBoxClearance: new google.maps.Size(20, 20),
		};
		infowindow = new InfoBox(myOptions);
	}

	$('#casasync_map_filter input').click(function(event) {
		var el = $(this).parent().parent();
		if($(el).attr('data-current') == 1) {
			$(el).attr('data-current', 0).find('input').prop('checked', false);
		} else {
			$(el).attr('data-current', 1).find('input').prop('checked', true);
		}

		refreshMarkers();
	});

	function renderInfoWindow(el) {
		var readMoreText = $('#casasync-map_map').data('readmore_text');

		var template = window.casasyncMapOptions.infobox_template ? $(window.casasyncMapOptions.infobox_template)[0].outerHTML : false;
		var options = {readMoreText: readMoreText};
		var i18n = false;
		if (window.casasyncMapOptions && window.casasyncMapOptions.i18n) {
			i18n = window.casasyncMapOptions.i18n;
		}
		var data = {"property" : el, "options" : options, "i18n" : i18n };

		Mustache.parse(template);
		var rendered = Mustache.render(template, data);
		infowindow.setContent(rendered);
		infowindow.open(map, marker);

		// move to click handler on marker
		google.maps.event.addListener(infowindow, 'domready', function(){
			$('#casasync-map_map').trigger( "cs_infowindow_open" );
		});
	}

	function refreshMarkers() {
		var url = getAjaxUrlForMarkers();
		var ajaxRequest = $.ajax({
			url: url,
			type: 'GET',
			dataType: 'json',
			data: {casasync_map: true}
		})
		.done(function(json) {
			deleteMarkers();
			$.each(json, function(index, el) {
				addMarker(el);
			});
		});
	}

	function getAjaxUrlForMarkers() {
		var urls = $('#casasync_map_filter').find('li[data-current="1"]');
		var result = $(urls[0]).data('url');
		urls.each(function(i, el){
			if (i != 0) {
				var url = $(el).data('url');
				result = result + '&' + url.substring(url.indexOf("?")+1);
			}
		})
		return result;
	}
});
