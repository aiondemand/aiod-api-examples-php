# Simple "Hello World!" example using PHP's cURL

On this example you are going to learn how to create a simple PHP script using [cURL](https://www.php.net/manual/en/book.curl.php), to send and retrieve data from the AIoD API and to even do some basic handling of the responses. The full version of the code can be found on [api-simple-example.php](./api-simple-example.php), which can be run directly via any terminal using the `php api-simple-example.php` command.

> Before starting, please make sure that the AIoDP API has been successfully installed on your local machine and that you can access it via your web browser at [localhost:8000](http://localhost:8000). For more information read the [Prerequisites](./../../README.md#prerequisites) and the [Getting Started](./../../README.md) guide.

## 1. Create a new PHP Script

To start, open a text/code editor and create a new file, e.g. '`simple-example.php`'.

## 2. Populate an array that represents the news item's data

Set up a basic PHP script that initialises and populates an array that represents the news item's data, as defined by the schema which can be found at the [AIoD API documentation](http://localhost:8000/docs). To keep things simple, only the required (and some optional) metadata could be filled:

```php
<?php

$data = [
    # Required fields:
    'title' => 'Hello World Simple Edition!',
    'dateModified' => '2023-05-30T14:22:00+00:00',
    'headline' => 'Hello World! The AIoD API salutes you!',
    'section' => 'Hello World News',
    'body' => 'This is the main body of the simple news item.',
    'wordCount' => 9,
    # Some optional fields:
    'source' => 'AIoD API Examples: PHP',
    'businessCategories' => ['Cloud, Edge and Infrastructure'],
    'keywords' => ['API', 'Education'],
];
```
>**TIP**: * `$data['dateModified']` could be set to `gmdate(DATE_ATOM)` for the current date and time in ISO 8601 format, while `$data['wordCount']` could be easily calculated at a later point with `str_word_count($data['body'])`.

## 3. Convert the data to JSON

The array of data can't be posted directly the API. It has to be converted to JSON format using `json_encode()`:
```php
$json_data = json_encode($data);
```

## 4. Define the endpoint and set up the cURL session

To insert data to the API an endpoint has to be used which allows the creation of a news item. This can also be easily found at the [AIoD API documentation](http://localhost:8000/docs).
```php
$url = 'http://localhost:8000/news/v0';
```

Then, the script should initialize a cURL session using `curl_init()` and sets various options such as making a `POST` request, setting the `$json_data` as the request body, and specifying the content type and length headers.
```php
$curl = curl_init($url);

$options = [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $json_data,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($json_data)
    ],
    CURLOPT_RETURNTRANSFER => true,
];

curl_setopt_array($curl, $options);
```

## 5. Send the data and get the response

The cURL session sends the request to the API and the response is stored in the $response variable.
```php
$response = curl_exec($curl);
```

And that's basically is all it takes to insert a news item to the AIoD API!

---

### Optional handling of the response

At this point the HTTP status code of the response could be checked using `curl_getinfo()` and if it's not 200 (OK) an error message could be displayed or an exception could be thrown.

```php
$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
if ($http_status !== 200) {
    // Some basic error handling could go here...
    echo "HTTP Status Code: " . $http_status . PHP_EOL;
} else {
    // But hopefully, there were no issues with our code!
    echo "Data inserted successfully!" . PHP_EOL;
}
```

---

Finally, the cURL session is closed using curl_close().
```php
curl_close($curl);
```

## 6. The 'Hello World!' greeting

... But, we are still missing a 'Hello World!' greeting, so let's get it! The `$response` from the API can be decoded from JSON to an associative array using `json_decode($response, true)`, and from that, we can extract the description and display it on screen using a simple good-old `echo`:

```php 
$response_data = json_decode($response, true);
$headline = $response_data['headline'];
echo $headline;
```

[ [Return to the Index of this repository](./../../README.md) ]