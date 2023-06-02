<?php
/* Insert your own organization to the API example.
 *
 * As long as PHP and the AIoD API is installed on your machine, you can run
 * this code by running "php example-basic-1-hello-world.php" on your terminal.
 * The expected result is "Hello World!", posted & retrieved via the API!
 */

// 1. Let's build an array that represents the basic organization's data:
$data = [
    "name" => "Hello World Organisation",
    "description" => "Hello World! The AIoD API salutes you!",
    "type" => "Education Institution",
    "businessCategories" => ["Cloud, Edge and Infrastructure"],
    "technicalCategories" => ["Knowledge Representation"]
];

// 2. The data must be converted to JSON:
$json_data = json_encode($data);

// 3. The local URL of the organisation endpoint:
$url = 'http://localhost:8000/organisations/v0';

// 4. We are going to use curl to "send" the data to the API...
// ... so let's initialise it:
$curl = curl_init($url);
// ... set the necessary options:
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($json_data)
]);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// ... and finally, send the request and get the response!
$response = curl_exec($curl);

// 5. Just to be sure, let's examine the "response" from the API:
$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
if ($http_status !== 200) {
    // Some basic error handling could go here...
    echo "HTTP Status Code: " . $http_status . PHP_EOL;
} else {
    // But hopefully, there were no issues with our code!
    echo "Data inserted successfully!" . PHP_EOL;
}

// 6. Close the session.
curl_close($curl);

// 7. And finally let's get our "Hello World"!
$response_data = json_decode($response, true);
$description = $response_data['description'];
echo $description  . PHP_EOL;
