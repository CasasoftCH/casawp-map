var map;
var markers = [];
var infowindow = false;
var marker;
var latlngbounds;
jQuery( function () {
	"use strict";
	var $ = jQuery;

	var map_xhr = null;
	var map_timeout = null;

	function initialize() {
		var switzerland = new google.maps.LatLng(46.8131873,8.2242101);

		var geocoder = new google.maps.Geocoder();
		geocoder.geocode( { 'address': 'switzerland'}, function(results, status) {
			if (map && status == google.maps.GeocoderStatus.OK) {
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
		var markerImage = '';
		if (window.casasyncMapOptions && window.casasyncMapOptions.marker_image) {
			markerImage = window.casasyncMapOptions.marker_image;
		}
		marker = new google.maps.Marker({
			position: new google.maps.LatLng(el.lat, el.lng),
			map: map,
			icon: markerImage,
			animation: google.maps.Animation.DROP,
			name: el.id,
			title: el.id+' hello'
		});
		markers.push(marker);

		google.maps.event.addListener(marker, 'click', (function() {
			renderInfoWindow(el);
		}));
	}

	function setInfoWindow() {
		var closeButton = false;
		if (window.casasyncMapOptions && window.casasyncMapOptions.plugin_url) {
			var closeButton = window.casasyncMapOptions.plugin_url + 'assets/img/close.png';
		}
		var myOptions = {
			//pixelOffset: new google.maps.Size(-140, 0),
			closeBoxMargin: "18px 5px 5px 5px",
			closeBoxURL: closeButton,
			infoBoxClearance: new google.maps.Size(20, 20),
		};
		infowindow = new google.maps.InfoWindow(myOptions);
	}
	
	refreshMarkers();

	$('#casasync_map_filter .term-checkbox input:checked').data('current', 1);

	$('#casasync_map_filter input').click(function(event) {
		var el = $(this).closest('.term-checkbox');
		if($(el).data('current') == 1) {
			$(el).data('current', 0).find('input').prop('checked', false);
			var current = 0;
		} else {
			$(el).data('current', 1).find('input').prop('checked', true);
			var current = 1;
		}


		 if (current == 1) {
		 	$(this).closest('.termgroup').find('.children .term-checkbox').data('current', 1);
		 	$(this).closest('.termgroup').find('input').prop('checked', true)
		 } else {
		 	$(this).closest('.termgroup').find('.children .term-checkbox').data('current', 0);
		 	$(this).closest('.termgroup').find('input').prop('checked', false);

		 	$(this).closest('.termgroup').parent().closest('.termgroup').find('.term-checkbox').first().data('current', 0).find('input').prop('checked', false);
		 	$(this).closest('.termgroup').parent().closest('.termgroup').parent().closest('.termgroup').find('.term-checkbox').first().data('current', 0).find('input').prop('checked', false);
		 };
		 refreshMarkers();
	});

	function renderInfoWindow(el) {
		marker = null;
		for (var i = markers.length - 1; i >= 0; i--) {
			if (markers[i].name == el.id) {
				marker = markers[i];
			}
		}
		
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
	}

	function refreshMarkers() {


		if(map_xhr && map_xhr.readyState != 4){
            map_xhr.abort();
        }

        $('.casasync-map-wrap').addClass('loading');

        if (map_timeout) {
        	clearTimeout(map_timeout);
        }

        map_timeout = setTimeout(function(){
        	var url = getAjaxUrlForMarkers();
        	map_xhr = $.ajax({
        		url: url,
        		type: 'GET',
        		dataType: 'json',
        		data: {casasync_map: true}
        	})
        	.done(function(json) {
        		deleteMarkers();
        		latlngbounds = new google.maps.LatLngBounds();

        		$.each(json, function(index, el) {
        			var position = new google.maps.LatLng(el.lat, el.lng);
        			if (el.lat && el.lng) {
        				addMarker(el);

        				latlngbounds.extend(position);
        			}
        		});
        		if (window.casasyncMapOptions && window.casasyncMapOptions.map_viewport == 'fitbounds') {
    				map.setCenter(latlngbounds.getCenter());
    				map.fitBounds(latlngbounds);
        		};
        	})
        	.always(function() {
        		$('.casasync-map-wrap').removeClass('loading');
        	});
        }, 300);
	}

	function getAjaxUrlForMarkers() {
		var urls = $('#casasync_map_filter').find('li[data-current="1"]');

		var default_query;
		if (window.casasyncMapOptions && window.casasyncMapOptions.map_default_query) {
			default_query = window.casasyncMapOptions.map_default_query;
		} else {
			default_query = '/immobilien';
		}
		var result =  default_query + $('#casasync_map_filter form').serialize();

		/*urls.each(function(i, el){
			if (i != 0) {
				var url = $(el).data('url');
				result = result + '&' + url.substring(url.indexOf("?")+1);
			}
		})*/
		return result;
	}
});




			

			
