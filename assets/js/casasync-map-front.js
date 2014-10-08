jQuery( function () {
	"use strict";

	var $ = jQuery;
	
	/* Google Maps
	*************************/

	function initialize() {
		var mapOptions = {
			zoom: 7,
			center: new google.maps.LatLng(46.4548574,7.9495519)
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

	google.maps.event.addDomListener(window, 'load', initialize);



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