<?
$lat = $_GET['lat'];
$lon = $_GET['lon'];
$catID = $_GET['cat'];
$oauth = "HVBP350OQHKOOCN0VD1ZS30VK5EWRDD13XBBKI2GRR4CC2WV";

$venue_json = file_get_contents("https://api.foursquare.com/v2/venues/search?ll=$lat,$lon&categoryId=$catID&oauth_token=$oauth&v=".date('Ydm'), true);
$venue_json = json_decode($venue_json, true);
$venue_arr  = $venue_json['response'];

print "<h2>Found ".sizeof($venue_arr['venues'])." matching venues</h2>";

for($i=0;$i<sizeof($venue_arr['venues']);$i++) {
	//store in variables
	$id=$venue_arr['venues'][$i]['id'];
	if(isset($venue_arr['venues'][$i]['name'])) { $name=$venue_arr['venues'][$i]['name']; } 								else { $missing=True; $name="<strong>Missing Name</strong>";}
	if(isset($venue_arr['venues'][$i]['location']['crossStreet'])) { $crossSt = "(".$venue_arr['venues'][$i]['location']['crossStreet'].")"; } else { $missing=True; $crossSt = "(<strong>Missing Cross Street</strong>)"; }
	if(isset($venue_arr['venues'][$i]['location']['address'])) { $address=$venue_arr['venues'][$i]['location']['address']; } 				else { $missing=True; $address="<strong>Missing Address</strong>"; }
	if(isset($venue_arr['venues'][$i]['location']['city'])) { $city=$venue_arr['venues'][$i]['location']['city'].", "; } 				else { $missing=True; $city="<strong>Missing City</strong>"; }
	if(isset($venue_arr['venues'][$i]['location']['state'])) { $state=$venue_arr['venues'][$i]['location']['state']; } 					else { $missing=True; $state="<strong>Missing State"; }
	if(isset($venue_arr['venues'][$i]['location']['postalCode'])) { $zip=$venue_arr['venues'][$i]['location']['postalCode']; } 				else { $missing=True; $zip="<strong>Missing Postal Code</strong>"; }

	//throw in a notice to edit if some data is missing.
	if (isset($missing)) {
		print "<p>This venue is missing some data, why not fix it? <a href=\"https://foursquare.com/v/".$id."/edit\">Edit Venue</a></p>";
	}
	print "<p><strong>".$name."</strong><br />\n";
	print $address;
	print " ".$crossSt."\n<br />";
	print $city;
	print $state." ";
	print $zip."<br />";
	print "</p>";

	print "<h3>In Categories:</h3>";

	for($x=0; $x<sizeof($venue_arr['venues'][$i]['categories']); $x++) {
		$category=$venue_arr['venues'][$i]['categories'][$x]['pluralName']. " ";
		$iconURL=$venue_arr['venues'][$i]['categories'][$x]['icon'];

		print "<img src=\"$iconURL\" alt=\"$category\" />";
		print " ".$category."<br />";
		
	}

	//open links.
	print "<p>";
	if (isset($city) && isset($state)) {
		print "<a href=\"http://www.google.com/search?q=".urlencode($name." near ".$city.$state)."\">Google It!</a>";
	} else {
		print "<a href=\"http://www.google.com/search?q=".urlencode($name)."\">Google It!</a>";
	}
	
	print " | ";

	if (isset($city) && isset($state)) {
		print "<a href=\"http://www.yelp.com/search?find_desc=".urlencode($name)."&ns=1&find_loc=".urlencode($city.$state)."\">Yelp It!</a>";
	} else {
		print "<a href=\"http://www.yelp.com/search?find_desc=".urlencode($name)."&ns=1&find_loc=\">Yelp It!</a>";
	}

	print " | ";
	print "<a href=\"https://foursquare.com/v/".$id."\">Open Venue</a>";
	print "</p>";
	
	print "<hr>";
}
?>