<?php

if (isset($_GET['departs'])){
	//echo $_GET['departs'];
	$departure = $_GET['departs'];
}

//Some time zones for testing
date_default_timezone_set('America/Los_Angeles'); //Use this one in 'production'
//date_default_timezone_set('Australia/Perth');
//date_default_timezone_set('Australia/Melbourne');

$current_time = new DateTime();
//$current_time = date("g:i A",time());

echo "<?xml version='1.0' encoding='UTF-8'?>"; //Outbound Xml for Studio to use
echo "<schedule>";
echo" <current_time>".$current_time->format('g:i A')."</current_time>";

//Connect to the Database: ferry  
$con = mysqli_connect("localhost","root","ASHBURTON174", "ferry"); 

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error()); //Argh!
    exit();
}

//Run the query
$query = "SELECT * FROM `Schedule` WHERE time > '".$current_time->format('G:i:m')."' AND `departs` LIKE '%".$departure."%' ORDER BY time LIMIT 1";
$result = mysqli_query($con,$query);

//printf("<p>Number of rows is %d.\n</p>", mysqli_num_rows($result));

//Cat the results
while ($row = $result->fetch_array()) {
	$next_time = new DateTime($row["time"]);
	printf ("<next>%s</next>", $next_time->format('g:i A'));
	
	//Calculate how far away the next ferry is in seconds
	$delta = $next_time->diff($current_time);
	$delta_in_seconds =  3600*$delta->format('%h') + 60*$delta->format('%i') + $delta->format('%s');
	printf ("<delta-seconds>%s</delta-seconds>", $delta_in_seconds);
	
// Now calculate when a reminder call is due
	$interval = new DateInterval("PT10M"); //10 minutes
	$interval->invert = 1;  //make time interval 'negative'
	$reminder_time = $next_time;
	$reminder_time->add($interval);  //make the reminder 10 minutes earlier than the ferry departure
	printf ("<reminder>%s</reminder>", $reminder_time->format(DateTime::ATOM));	 //format the time for use in the Studio Connect API
	
}
	echo "</schedule>";  //Close off the XML
	
//Free the result set
mysqli_free_result($result);

//Close the DB
mysqli_close($con); 
?>


