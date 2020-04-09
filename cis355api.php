<?php 
/*
	filename 	: cis355api.php
	author   	: george corser
	course   	: cis355 (winter2020)
	description	: demonstrate JSON API functions
				  return number of new covid19 cases
	input    	: https://api.covid19api.com/summary
	functions   : main()
	                curl_get_contents()
*/

main();

#-----------------------------------------------------------------------------
# FUNCTIONS
#-----------------------------------------------------------------------------
function main () {
	
	$apiCall = 'https://api.covid19api.com/summary';
	// line below stopped working on CSIS server
	// $json_string = file_get_contents($apiCall); 
	$json_string = curl_get_contents($apiCall);
	$obj = json_decode($json_string);
	$data = $obj->Countries;

	// echo html head section
	echo '<html>';
	echo '<head>';
    echo '<meta charset="utf-8">';
    echo '<link   href="css/bootstrap.min.css" rel="stylesheet">';
    echo '<script src="js/bootstrap.min.js"></script>';
    echo '</head>';
	
	// open html body section
	echo '<body onload="loadDoc()">';
	
	echo '<div>';
	$count = 0;
	$otherArray = array();
	$myArray = array($data) ;
	foreach ($data as $value) {
	$otherArray[$value->Country] = $value->TotalConfirmed;
	}
	arsort($otherArray);
	echo '<table class="table table-striped table-bordered" style="background-color: lightgrey !important">';
	echo '<tr>';
	echo '<th>Country</th>';
	echo '<th>Cases</th>';
	echo '</tr>';
	foreach ($otherArray as $key => $value){
	    echo '<tr>';
	    echo '<td>'.$key.'</td>';
	    echo '<td>'.$value.'</td>';
	    echo '<tr>';
	    $count++;
	    if($count == 10)
	    break;
	}
	echo '</div>';
	
	echo '<div id="demo">';
	echo '</div>';
	
	// close html body section
	echo '</body>';
	echo '</html>';
}


#-----------------------------------------------------------------------------
// read data from a URL into a string
function curl_get_contents($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
?>












