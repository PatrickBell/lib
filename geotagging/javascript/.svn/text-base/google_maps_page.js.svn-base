var geotaggedChildren = eval("$GeotaggedChildren");

google.load("maps", "2");

function initialize() {
	if (GBrowserIsCompatible()) {
		// Set up the map
		var map = new google.maps.Map2(document.getElementById('GoogleMapCanvas'));

		// Initialise bounds
		var bounds = new google.maps.LatLngBounds();

		map.setCenter(new google.maps.LatLng(-41.244772, 172.617188), 8);
		map.setUIToDefault();

		// disable scroll wheel zooming
		map.disableScrollWheelZoom(); 

		// Add clickable and popup-able markers
		if (geotaggedChildren.length) {
			for (var i=0; i<geotaggedChildren.length; i++) {
				(function() {
					var loc = geotaggedChildren[i];
					var point = new GLatLng(loc.lat, loc.long);

					//Register each point for bounds
					bounds.extend(point);

					var markerOptions = {};
					if (loc.icon) {
						markerOptions.icon = new GIcon();
						markerOptions.icon.image = loc.icon;
						markerOptions.icon.iconSize = new GSize(32, 32);
						markerOptions.icon.iconAnchor = new GPoint(16, 32);
					}
					var marker = new GMarker(point, markerOptions);

					map.addOverlay(marker);

					GEvent.addListener(marker, "click", function() {
						var popupHtml = loc.info;
						map.openInfoWindowHtml(point, popupHtml);
					});
				})();
			}
		}

		// Center and zoom map to bounds if MapZoom is zero (not set)
		if ($MapZoom == 0) {
			var BoundsZoom = map.getBoundsZoomLevel(bounds) < 12 ? map.getBoundsZoomLevel(bounds) : 12;
		} else {
			var BoundsZoom = $MapZoom;
		}
		map.setCenter(bounds.getCenter(), BoundsZoom);
	}
}
google.setOnLoadCallback(initialize);
