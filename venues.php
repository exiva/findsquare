<?
$venue_json = file_get_contents("http://4sq.exiva.net/search-output.json", true);
$venue_json = json_decode($venue_json, true);
$venue_arr  = $venue_json['response'];

print "<h2>Found ".sizeof($venue_arr['venues'])." venues</h2>";

for($i=0;$i<sizeof($venue_arr['venues']);$i++) {
	print $venue_arr['venues'][$i]['name']." ";
	print $venue_arr['venues'][$i]['id']." <br>";
	for($x=0; $x<sizeof($venue_arr['venues'][$i]['categories']); $x++) {
		print $venue_arr['venues'][$i]['categories'][$x]['pluralName']. " ";
		print $venue_arr['venues'][$i]['categories'][$x]['icon']. " <br>";
	}
print "<br>";
}