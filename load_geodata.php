<?php
$data = array_map('str_getcsv', file('cityzip.csv'));
unset($data[0]); // first index are headers

$db = new PDO('mysql:host=localhost;dbname=locations',
            'root',
            '90876dtDT'); // PUT IN YOUR PASSWORD

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