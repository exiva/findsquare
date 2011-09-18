<?
//pulls in category data from foursquare and displays
$cat_json = file_get_contents("https://api.foursquare.com/v2/venues/categories?oauth_token=HVBP350OQHKOOCN0VD1ZS30VK5EWRDD13XBBKI2GRR4CC2WV", true);
$cat_jsondec = json_decode($cat_json, true);
$cat_arr = $cat_jsondec['response'];
?>
<select name="categories" onChange="window.catID=this.value;">
<option>Select a category</option>
<?
for($i=0;$i<sizeof($cat_arr['categories']);$i++) {
	print "<optgroup label=\"";
	print $cat_arr['categories'][$i]['name'];
	print "\">";

	for($x=0;$x<sizeof($cat_arr['categories'][$i]['categories']);$x++) {
		print "<option value=\"";
		print $cat_arr['categories'][$i]['categories'][$x]['id'];
		print "\">";
		print $cat_arr['categories'][$i]['categories'][$x]['pluralName'];
		
		print "</option>";
	}
}
print "</select>";
?>