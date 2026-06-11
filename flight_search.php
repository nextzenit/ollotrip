

<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://server.bookingexpert.us/api/v2/b2c/flight/search/sse?JourneyType=2&PassengerTypeQuantity=[{%22Code%22%3A%22ADT%22%2C%22Quantity%22%3A1}]&OriginDestinationInformation=[{%22RPH%22%3A%221%22%2C%22DepartureDateTime%22%3A%222026-02-24T06%3A00%3A00%22%2C%22OriginLocation%22%3A{%22LocationCode%22%3A%22DAC%22}%2C%22DestinationLocation%22%3A{%22LocationCode%22%3A%22CAN%22}%2C%22TPA_Extensions%22%3A{%22CabinPref%22%3A{%22Cabin%22%3A%221%22%2C%22PreferLevel%22%3A%22Preferred%22}}}%2C{%22RPH%22%3A%222%22%2C%22DepartureDateTime%22%3A%222026-02-26T06%3A00%3A00%22%2C%22OriginLocation%22%3A{%22LocationCode%22%3A%22CAN%22}%2C%22DestinationLocation%22%3A{%22LocationCode%22%3A%22DAC%22}%2C%22TPA_Extensions%22%3A{%22CabinPref%22%3A{%22Cabin%22%3A%221%22%2C%22PreferLevel%22%3A%22Preferred%22}}}]',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);
curl_close($curl);

// Split response by line
$lines = explode("\n", $response);

$results = [];

foreach ($lines as $line) {
    $line = trim($line);

    if (strpos($line, 'data:') === 0) {
        $json = trim(substr($line, 5)); // remove "data:"
        
        $decoded = json_decode($json, true);
        
        if ($decoded) {
            $results[] = $decoded;
        }
    }
}

header('Content-Type: application/json');
echo json_encode($results, JSON_PRETTY_PRINT);
?>