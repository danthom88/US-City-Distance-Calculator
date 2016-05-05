<?php
//Make sure information was passed along.

$response = array();

$combine = array();
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
	
	return $distance;
}


$servername = "localhost";
$username = "root";
$password = "devpass"; //ENTER YOUR PASSWORD
$dbname = "locations";
$city = $_GET['city'];
$state = $_GET['state'];


$conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " .$conn->connect_error);
    }

$sql = "SELECT lati, longi FROM geocode WHERE city='$city' AND state='$state' LIMIT 1";
$sql2 = "SELECT state, city, lati, longi FROM geocode WHERE state='$state'";
$result1 = $conn->query($sql);
$result2 = $conn->query($sql2);

$pulled_lat1;
$pulled_lat2;
$pulled_long1;
$pulled_long2;

    if ($result1->num_rows >0) {
        
        //Getting cordinates of original city/state
        while($row = $result1->fetch_assoc()) {
            $pulled_lat1 = $row["lati"];
            $pulled_long1 = $row["longi"];
        }
        //Run distance function on all cities of same state
         while ($row = $result2->fetch_assoc()) {

            $distance = Haversine($pulled_lat1, $pulled_long1, $row['lati'], $row['longi'], $row['city'], $row['state']);
            
             if ($distance <= $_GET['distance']) {
                 $combine = array (
                 'city' => $row['city'],
                 'state' => $row['state'],
                 'distance' => $distance);
                 array_push ($response, $combine);
             }

            
    }    

    $sortResponse = array();
    foreach ($response as $key => $row)
    {
    $sortResponse[$key] = $row['distance'];
    }
    array_multisort($sortResponse, SORT_ASC, $response);

    $response = json_encode($response);
    echo $response;   

    }
    else {
        echo 'City and State not found in the Database!';
    }

   
?>
