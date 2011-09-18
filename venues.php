<?
$venue_json = file_get_contents("http://4sq.exiva.net/search-output.json", true);
$venue_json = json_decode($venue_json, true);
$venue_arr  = $venue_json['response'];

print "<h2>Found ".sizeof($venue_arr['venues'])." matching venues</h2>";

for($i=0;$i<sizeof($venue_arr['venues']);$i++) {
	//store in variables
	$id=$venue_arr['venues'][$i]['id'];
	if(isset($venue_arr['venues'][$i]['name'])) 					{ $name=$venue_arr['venues'][$i]['name']; } 								else { $missing=True; $name="<strong>Missing Name</strong>";}
	if(isset($venue_arr['venues'][$i]['location']['crossStreet'])) 	{ $crossSt = "(".$venue_arr['venues'][$i]['location']['crossStreet'].")"; } else { $missing=True; $crossSt = "(<strong>Missing Cross Street</strong>)"; }
	if(isset($venue_arr['venues'][$i]['location']['address'])) 		{ $address=$venue_arr['venues'][$i]['location']['address']; } 				else { $missing=True; $address="<strong>Missing Address</strong>"; }
	if(isset($venue_arr['venues'][$i]['location']['city'])) 		{ $city=$venue_arr['venues'][$i]['location']['city'].", "; } 				else { $missing=True; $city="<strong>Missing City</strong>"; }
	if(isset($venue_arr['venues'][$i]['location']['state'])) 		{ $state=$venue_arr['venues'][$i]['location']['state']; } 					else { $missing=True; $state="<strong>Missing State"; }
	if(isset($venue_arr['venues'][$i]['location']['postalCode'])) 	{ $zip=$venue_arr['venues'][$i]['location']['postalCode']; } 				else { $missing=True; $zip="<strong>Missing Postal Code</strong>"; }

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
	print "Google It!";
	print " | ";
	print "Yelp It!";
	print " | ";
	print "<a href=\"https://foursquare.com/v/".$id."\">Open Venue</a>";
	print "</p>";
	
	print "<hr>";
}


?>