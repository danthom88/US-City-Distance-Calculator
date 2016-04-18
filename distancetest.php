<?php
// Get Distance between two lat/lng points using the Haversine function
// First published by Roger Sinnott in Sky & Telescope magazine in 1984 (“Virtues of the Haversine”)
//
$setlocations = false;
function Haversine( $lat1, $lon1, $lat2, $lon2, $city, $state) 
{
    $R = 6372.8;	// Radius of the Earth in Km

	// Convert degress to radians and get the distance between the latitude and longitude pairs
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

	// Calculate the angle between the points
    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
    $c = 2 * asin(sqrt($a));
    $d = $R * $c;

	// Distance in Kilometers
    $distance = round(0.6214*$d ,0);
	
	if ($distance <= 20) {
		echo "<br>" . $city . ", " . $state . " is " . $distance . " away from selected city.";
	}
}

if(ISSET($_POST["city1"]) && ISSET($_POST["state1"])) {
$setlocations = true;
}
?>

<html>
<head><title>Testing distances calculation</title></head>
<body>

<?php
if ($setlocations){
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "locations";
$city1 = $_POST["city1"];
$state1 = $_POST["state1"];


$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
	die("Connection failed: " .$conn->connect_error);
}

$sql = "SELECT lati, longi FROM geocode WHERE city='$city1' AND state='$state1' LIMIT 1";
$sql2 = "SELECT state, city, lati, longi FROM geocode WHERE state='$state1'";
$result1 = $conn->query($sql);
$result2 = $conn->query($sql2);

$pulled_lat1;
$pulled_lat2;
$pulled_long1;
$pulled_long2;
$returnedisbroken=false;

if ($result1->num_rows >0) {
	while($row = $result1->fetch_assoc()) {
		$pulled_lat1 = $row["lati"];
		$pulled_long1 = $row["longi"];
		echo "Selecting cities within 20 miles of select city.";
	}
}
else {$returnedisbroken=true;}

while ($row = $result2->fetch_assoc()) {
	
		Haversine($pulled_lat1, $pulled_long1, $row['lati'], $row['longi'], $row['city'], $row['state']);
	
}

if ($returnedisbroken) {
	echo 'No results!';
}
else {
	
}

}

 // if field-data is set
else {
echo '<form action="distancetest.php" method="post">';
echo 'City <input type="textbox" name="city1"> State<input type="textbox" name="state1"><br>';
echo '<input type="submit">';
echo '</form>';
}
?>
</body>
</html>