<?php
/* Simple example: Insert a news item to the AIoD API using PHP's cURL.
 *
 * As long as PHP and the AIoD API is installed on your machine, you can run
 * this code by running "php api-simple-example.php" on your terminal. The expected
 * result is "Hello World!", posted & retrieved via the API!
 */

# Populates an array that represents the news item's data:
$data = [
    // Required fields:
    'title' => 'Hello World Simple Edition!',
    'dateModified' => '2023-05-30T14:22:00+00:00',
    'headline' => 'Hello World! The AIoD API salutes you!',
    'section' => 'Hello World News',
    'body' => 'This is the main body of the simple news item.',
    'wordCount' => 9,
    // Some optional fields:
    'source' => 'AIoD API Examples: PHP',
    'businessCategories' => ['Cloud, Edge and Infrastructure'],
    'keywords' => ['API', 'Education'],
];

# Converts the data to JSON:
$json_data = json_encode($data);

# Defines the endpoint:
$url = 'http://127.0.0.1:8000/news/v0';

# Set ups the cURL session:
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($json_data)
]);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

# Sends the data and gets the response:
$response = curl_exec($curl);

# Basic handling of the response:
$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
if ($http_status !== 200) {
    // Some basic error handling could go here...
    echo "HTTP Status Code: " . $http_status . PHP_EOL;
} else {
    // But hopefully, there were no issues with our code!
    echo "Data inserted successfully!" . PHP_EOL;
}

# Closes the cURL session:
curl_close($curl);

# Gets the "Hello World!" greeting:
$response_data = json_decode($response, true);
print_r($response_data);
echo $response_data['headline']  . PHP_EOL;
