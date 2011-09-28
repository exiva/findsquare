function loadVenues(oauth, date) {
	//load venue data
	var venue_url = "https://api.foursquare.com/v2/venues/search";
	$.getJSON(venue_url+'?ll='+window.lat+','+window.lon+'&categoryId='+window.catID+'&radius=20000&oauth_token='+oauth+'&v='+date, function(venue_json){
	document.getElementById('venuelist').innerHTML = "";
	if (venue_json.meta.code == 200) {
		var l = venue_json.response.venues.length;
		$("#venuelist").append('<h2>Found '+l+' matching venues</h2>');
		//run through the venues we found.
		$.each(venue_json.response.venues, function(i, venue) {
			var name = venue.name;
			var address = venue.location.address;
			var crossStreet = venue.location.crossStreet;
			var city = venue.location.city;
			var state = venue.location.state;
			var zip = venue.location.postalCode;
			var missing = false;
			if (name==undefined) { missing=true; name="<strong>Missing Name</strong>"; };
			if (address==undefined) { missing=true; address="<strong>Missing Address</strong>"; };
			if (crossStreet==undefined) { missing=true; crossStreet="<strong>Missing Cross Street</strong>"; };
			if (city==undefined) { missing=true; city="<strong>Missing City</strong>"; };
			if (state==undefined) { missing=true; state="<strong>Missing State</strong>"; };
			if (zip==undefined) { missing=true; zip="<strong>Missing Postal Code</strong>"}
										
			if (missing==true) {$("#venuelist").append('<p>This venue is missing some data, why not fix it? <a href="http://foursquare.com/v/'+venue.id+'/edit">Edit Venue</a></p>')};
			$("#venuelist").append('<p><strong>'+name+'</strong><br />');
			$("#venuelist").append(address+' ('+crossStreet+')<br />');
			$("#venuelist").append(city+', '+state+' '+zip+'</p>');
			$("#venuelist").append("<h3>In Categories:</h3><p>");
			$.each(venue.categories, function(i, categories) {
				var category = categories.pluralName;
				var iconURL = categories.icon;
				$("#venuelist").append('<img src="'+iconURL+'" alt="'+category+'" /> '+category+'<br />');
			});
			$("#venuelist").append('</p<p>');
										
			if (venue.location.city==undefined || venue.location.state==undefined) {
				$("#venuelist").append('<a href="http://www.google.com/search?q='+encodeURI(name)+'">Google It!</a>');
				$("#venuelist").append(' | <a href="http://www.yelp.com/search?find_desc='+encodeURI(name)+'&ns=1&find_loc=">Yelp It!</a>');
				$("#venuelist").append(' | <a href="https://foursquare.com/v/'+venue.id+'">Open Venue</a></p>');
			} else {
				$("#venuelist").append('<a href="http://www.google.com/search?q='+encodeURI(name)+' near '+city+', '+state+'">Google It!</a>');
				$("#venuelist").append(' | <a href="http://www.yelp.com/search?find_desc='+encodeURI(name)+'&ns=1&find_loc='+encodeURI(city+', '+state)+'">Yelp It!</a>');
				$("#venuelist").append(' | <a href="https://foursquare.com/v/'+venue.id+'">Open Venue</a></p>');
			};

			$("#venuelist").append('<hr>');
		});
	};
	});
};

function loadCategories(oauth) {
	var cat_url = 'https://api.foursquare.com/v2/venues/categories?oauth_token='+oauth;
	$.getJSON(cat_url,function(cat_json){
		if (cat_json.meta.code == 200) {
			document.getElementById('categories').innerHTML = "";
			$("#categories").append('<option>Select a Category</option>');
			$.each(cat_json.response.categories, function(i, category) {
				$("#categories").append('<optgroup label="'+category.name+'">');
				$.each(category.categories, function(i, data) {
					$("#categories").append('<option value="'+data.id+'">'+data.pluralName+'</option>');
				});
			});
		};
	});
};

function getLocation(oAuth, date) {
	var data_url = 'https://api.foursquare.com/v2/users/self?oauth_token='+oAuth+'&v='+date;
	$.getJSON(data_url, function(profile_json){
		if (profile_json.meta.code == 200) {
			updateMap(profile_json.response.user.homeCity);
		};
	});
};

function loadMap(address) {
	var geocoders = new GClientGeocoder();
	geocoders.getLatLng(address, function(point) {
		var map = new GMap2(document.getElementById("map_canvas"));
		map.setUIToDefault();
		map.setCenter(point, 13);
		var marker = new GMarker(point, {draggable: true});
		window.lat = marker.getPoint().y;
		window.lon = marker.getPoint().x;
		GEvent.addListener(marker, "dragend", function(overlay, point) {
			window.lat = marker.getPoint().y;
			window.lon = marker.getPoint().x;
		});
		map.addOverlay(marker);				
	});
};

function login(oAuth, date) {
	var data_url = 'https://api.foursquare.com/v2/users/self?oauth_token='+oAuth+'&v='+date;
	var status = new Boolean();
	var request = $.ajax({
		url: data_url,
		dataType: 'json',
		async: false,
	});
						
	request.fail(function(){
		status = false;
		return status;
	});

	request.done(function(profile_json) {
		if (profile_json.meta.code == 200) {
			loadMap(profile_json.response.user.homeCity);
			loadCategories(oAuth);
			status = true;
		} else {
			status = false;
		};
		return status;
	});
	return status
};

function updateMap(address) {
	var geocoder = new GClientGeocoder();
	geocoder.getLatLng(address, goToMap);
};

function goToMap(latlng) {
	if (!latlng) {
		alert(address+' Not found');
	} else {
		map.clearOverlays();
		map.setCenter(latlng, 10);
		var marker = new GMarker(latlng, {draggable: true});
		map.addOverlay(marker);
			window.lat = marker.getPoint().y;
			window.lon = marker.getPoint().x;
	}					
};