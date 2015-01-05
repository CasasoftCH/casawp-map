jQuery( function () {
	"use strict";

	var $ = jQuery;

	var map;
	var markers = [];
	var infowindow = false;
	var marker;

	function initialize() {
	var switzerland = new google.maps.LatLng(46.8131873,8.2242101);


	var geocoder = new google.maps.Geocoder();
	geocoder.geocode( { 'address': 'switzerland'}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			map.setCenter(results[0].geometry.location);
			map.fitBounds(results[0].geometry.viewport);
		}
	});

	var mapOptions = {
		center: switzerland,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
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

	function addMarker(el) {
		infowindow.close(map);

		var markerImage = '';
		if (window.casasyncMapOptions && window.casasyncMapOptions.marker_image) {
			markerImage = window.casasyncMapOptions.marker_image;
		}
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(el.lat, el.lng),
			map: map,
			icon: markerImage,
		});
		markers.push(marker);

		google.maps.event.addListener(marker, 'click', (function(marker) {
			return function() {
				var contentString = '<div class="infowindow">'+
					'<h2>'+el.title+'</h2>'+
					'<div class="bodyContent">'+
					'<div class="attachedImage"><img src="'+el.img_src+'" alt="" /></div>'+
					'<a href="' + el.permalink + '" class="btn btn-default" target="_blank">Details</a>'+
					'</div>'+
					'</div>';
				infowindow.setContent(contentString);
				infowindow.open(map, marker);
			}
		})(marker));
	}

	// Sets the map on all markers in the array.
	function setAllMap(map) {
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(map);
		}
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

	// Removes the markers from the map, but keeps them in the array.
	function clearMarkers() {
		setAllMap(null);
	}

	// Deletes all markers in the array by removing references to them.
	function deleteMarkers() {
		clearMarkers();
		markers = [];
	}

	google.maps.event.addDomListener(window, 'load', initialize);

	$('#casasync_map_filter li').click(function(event) {
		$('#casasync_map_filter li[data-current="1"]').attr('data-current', 0)
		$(this).attr('data-current', 1)
		refreshMarkers();
	});

	function refreshMarkers() {
		var url = $('#casasync_map_filter').find('li[data-current="1"]').attr('data-url');
		var ajaxRequest = $.ajax({
			url: url,
			type: 'GET',
			dataType: 'json',
			data: {casasync_map: true},
		})
		.done(function(json) {
			deleteMarkers();
			$.each(json, function(index, el) {
				addMarker(el);
			});
		});
	}
});
