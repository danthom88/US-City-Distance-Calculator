<?php
$data = array_map('str_getcsv', file('cityzip.csv'));
unset($data[0]); // first index are headers

$db = new PDO('mysql:host=localhost;dbname=locations',
            'root',
<<<<<<< HEAD
            '90876dtDT'); // PUT IN YOUR PASSWORD
=======
            'password'); // PUT IN YOUR PASSWORD
>>>>>>> a10bf8161f6ccc84dfc1b6299dcfd3004955c2d0

$sql = "INSERT INTO geocode (state, city, lati, longi)
        VALUES (:state, :city, :lati, :longi)";

foreach ($data as $geocode)
{
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':state', $geocode[0]);
    $stmt->bindParam(':city', $geocode[1]);
    $stmt->bindParam(':lati', $geocode[3]);
    $stmt->bindParam(':longi', $geocode[4]);
    $stmt->execute();
}

?>