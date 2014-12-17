jQuery( function () {
	"use strict";

	var $ = jQuery;
	
	/* Google Maps
	*************************/

	/*var propertiesJson = jQuery('#casasync-map_map').attr('data-properties');
	// [id, title, permalink, lat, lng, img_src]
	var properties = jQuery.parseJSON( propertiesJson );

	var map = new google.maps.Map(document.getElementById('casasync-map_map'), {
		zoom: 8,
		center: new google.maps.LatLng(46.8131873,8.2242101), // center on switzerland
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});

	var infowindow = new google.maps.InfoWindow();

	var marker, i;

	for (i = 0; i < properties.length; i++) {
		marker = new google.maps.Marker({
			position: new google.maps.LatLng(properties[i]['lng'], properties[i]['lat']),
			map: map
		});

		google.maps.event.addListener(marker, 'click', (function(marker, i) {
			return function() {
				var content = '<div class="infoWindow">'+
				'<strong>'+properties[i]['title']+'</strong>'+
				'<img src="'+properties[i]['img_src']+'">'+
				'<a href="'+properties[i]['permalink']+'">Details anzeigen</a>';
				infowindow.setContent(content);
				infowindow.open(map, marker);
			}
		})(marker, i));
	}*/

	var map;
	var markers = [];

	function initialize() {
	var switzerland = new google.maps.LatLng(46.8131873,8.2242101)
	var mapOptions = {
		zoom: 7,
		center: switzerland,
	};
	map = new google.maps.Map(document.getElementById('casasync-map_map'), mapOptions);

	// set marker when map has loaded
	google.maps.event.addListenerOnce(map, 'tilesloaded', function(){
		refreshMarkers();
	});
}

	function addMarker(location) {
		var marker = new google.maps.Marker({
			position: location,
			map: map,
			//shadow: shadow,
			//icon: image,
			//Komplexe Symbole
			//https://developers.google.com/maps/documentation/javascript/overlays?hl=de

		});
		markers.push(marker);
	}

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

	google.maps.event.addDomListener(window, 'load', initialize);
	// ajax
	$('#casasync_map_filter').change(function(event) {
		refreshMarkers();
	});

	function refreshMarkers() {
		var url = $('#casasync_map_filter').find('.radio:checked').attr('data-url');
		var ajaxRequest = $.ajax({
			url: url,
			type: 'GET',
			dataType: 'json',
			data: {casasync_map: true},
		})
		.done(function(json) {
			deleteMarkers();
			$.each(json, function(index, el) {
				addMarker(new google.maps.LatLng(el.lng, el.lat));
			});
		});
	}


	// Works
	/*function initialize() {
		var mapOptions = {
			zoom: 8,
			center: new google.maps.LatLng(46.8131873,8.2242101) // center on switzerland
		};

		var map = new google.maps.Map(document.getElementById('casasync-map_map'), mapOptions);

		var propertiesJson = jQuery('#casasync-map_map').attr('data-properties');
		var properties = jQuery.parseJSON( propertiesJson )

		jQuery.each(properties, function (i, property) {
			var myLatLng = new google.maps.LatLng(property.lng, property.lat);
			var marker = new google.maps.Marker({
				position: myLatLng,
				map: map,
				//icon: image,
				title: property.title,
			});
			marker.setTitle((i + 1).toString());
			attachMessage(marker, property.title);
		});
	}

	function attachMessage(marker, title) {
		var infowindow = new google.maps.InfoWindow({
			content: title
		});

		google.maps.event.addListener(marker, 'click', function() {
			infowindow.open(marker.get('map'), marker);
		});
	}

	google.maps.event.addDomListener(window, 'load', initialize);*/



	// OLD

	// The following example creates complex markers to indicate beaches near
	// Sydney, NSW, Australia. Note that the anchor is set to
	// (0,32) to correspond to the base of the flagpole.

	/*function initialize() {
		var mapOptions = {
			zoom: 7,
			center: new google.maps.LatLng(46.4548574,7.9495519)
		}

		var map = new google.maps.Map(document.getElementById('casasync-map_map'), mapOptions);
	

		var propertiesJson = jQuery('#casasync-map_map').attr('data-properties');
		var properties = jQuery.parseJSON( propertiesJson );
		setMarkers(map, properties);
	}


	function setMarkers(map, properties) {
		// Add markers to the map

		// Marker sizes are expressed as a Size of X,Y
		// where the origin of the image (0,0) is located
		// in the top left of the image.

		// Origins, anchor positions and coordinates of the marker
		// increase in the X direction to the right and in
		// the Y direction down.
		var image = {
			url: '../img/marker.jpg',
			// This marker is 20 pixels wide by 32 pixels tall.
			size: new google.maps.Size(20, 32),
			// The origin for this image is 0,0.
			origin: new google.maps.Point(0,0),
			// The anchor for this image is the base of the flagpole at 0,32.
			anchor: new google.maps.Point(0, 32)
		};
		// Shapes define the clickable region of the icon.
		// The type defines an HTML &lt;area&gt; element 'poly' which
		// traces out a polygon as a series of X,Y points. The final
		// coordinate closes the poly by connecting to the first
		// coordinate.

		jQuery.each(properties, function (i, property) {
			var myLatLng = new google.maps.LatLng(property.lng, property.lat);
			var marker = new google.maps.Marker({
				position: myLatLng,
				map: map,
				//icon: image,
				title: property.title,
			});
		});
	}

	google.maps.event.addDomListener(window, 'load', initialize);*/






});
