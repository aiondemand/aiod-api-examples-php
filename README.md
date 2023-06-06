# AIoD API Examples: PHP

This repository contains a few easy to follow examples build in PHP, which utilise the AI-on-Demand API ([AIoD API](https://github.com/aiondemand/AIOD-rest-api) version [0.3.20220501](https://github.com/aiondemand/AIOD-rest-api/releases/tag/0.3.20220501)) to retrieve and store data. 

## Index

- [Prerequisites](./README.md#prerequisites): A local PHP development environment and a few other things...
- [Getting started](./README.md#getting-started): How to install the API using Docker.
- [Simple "Hello World!" example (using PHP's cURL to insert a News item)](./README.md#simple--hello-world---example-using-phps-curl): It couldn't be simpler!
- [Advanced example (using API client libraries to insert a News item)](./README.md#advanced-example--creating--using-api-client-libraries-with-swagger-codegen): Although more advanced, it's just as easy!


## Prerequisites

- Basic understanding of the PHP coding language
- A local development environment which allows the execution of PHP code
- [Docker](https://www.docker.com/) to install & run the AI-on-Demand API ([AIoD API](https://github.com/aiondemand/AIOD-rest-api)) 
- [Composer](https://getcomposer.org/) for the *advanced* example

---

## Getting started

Clone the [AIoD API repository](https://github.com/aiondemand/AIOD-rest-api) to your local machine:
```shell
git clone git@github.com:aiondemand/AIOD-rest-api.git
```

Change to the newly created folder:
```shell
cd AIOD-rest-api
```

Switch to version 0.3.20220501 of the API:
```shell
git checkout tags/0.3.20220501 -b 0.3.20220501
```

Make sure that Docker is running on your machine and then install the AIoD API by following the [installation instructions](https://github.com/aiondemand/AIOD-rest-api/blob/0.3.20220501/README.md#installation).
```shell
docker network create sql-network
docker run -e MYSQL_ROOT_PASSWORD=ok --name sqlserver --network sql-network -d mysql
docker build --tag ai4eu_server_demo:latest -f Dockerfile .
docker run --network sql-network -it -p 8000:8000 --name apiserver ai4eu_server_demo
```

>**TIP**: *There's no reason to repeat this process after the initial installation, as you can start the AIoD API by running `docker start sqlserver` followed by `docker start apiserver`.*

---

At this point you should be able to access the API using your web browser at [localhost:8000](http://localhost:8000). This instance operates at your own machine independently of the actual AIoD API, ensuring that real data is not accidentally modified or corrupted. In other words, it's the ideal playground for development or testing!

---

## Simple "Hello World!" example using PHP's cURL

On this example you are going to learn how to create a simple PHP script using [cURL](https://www.php.net/manual/en/book.curl.php), to send and retrieve data from the AIoD API and to even do some basic handling of the responses. The full version of the code can be found on [api-simple-example.php](./api-simple-example.php), which can be run directly via any terminal using the `php api-simple-example.php` command.

### 1. Create a new PHP Script

To start, open a text/code editor and create a new file, e.g. '`simple-example.php`'.

### 2. Populate an array that represents the news item's data

Set up a basic PHP script that initialises and populates an array that represents the news item's data, as defined by the schema which can be found at the [AIoD API's documentation](http://localhost:8000/docs). To keep things simple, only the required (and some optional) metadata could be filled:

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
>**TIP**: * `$data['dateModified']` could be set to `gmdate(DATE_ATOM)` for the current date and time in ISO 8601 format, while `$data['wordCount']` could be easily calcuated at a later point with `str_word_count($data['body'])`.

### 3. Convert the data to JSON

The array of data can't be posted directly the API. It has to be converted to JSON format using `json_encode()`:
```php
$json_data = json_encode($data);
```

### 4. Define the endpoint and set up the cURL session

To insert data to the API an endpoint has to be used which allows the creation of a news item. This can also be easily found at the [AIoD API's documentation](http://localhost:8000/docs). 
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

### 5. Send the data and get the response

The cURL session sends the request to the API and the response is stored in the $response variable.
```php
$response = curl_exec($curl);
```

And that's basically is all it takes to insert a news item to the AIoD API!

---

#### Optional handling of the response

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

### 6. The 'Hello World!' greeting

... But, we are still missing a 'Hello World!' greeting, so let's get it! The `$response` from the API can be decoded from JSON to an associative array using `json_decode($response, true)`, and from that, we can extract the description and display it on screen using a simple good-old `echo`:

```php 
$response_data = json_decode($response, true);
$headline = $response_data['headline'];
echo $headline;
```
---

## Advanced example: Creating & using API client libraries with Swagger Codegen

The AIoD API follows the [OpenAPI](https://github.com/OAI/OpenAPI-Specification) specification and therefore a vast amount of tools is available to help developers build apps which utilise its capabilities with minimum effort. On this example you are going to learn how to use the [Swagger Codegen](https://github.com/swagger-api/swagger-codegen) (a project which allows generation of API client libraries given an **OpenAPI Spec**) to post and retrieve data from the AIoD API.

### Installing the required dependencies

#### 1. Get the OpenAPI Spec for the AIoD API

Simply download the OpenAPI Spec for your local instance of the AIoD API via [localhost:8000/openapi.json](http://localhost:8000/openapi.json). The `openapi.json` file contains all the information needed to access the endpoints of the API to insert and/or retrieve data.

#### 2. Create the API client libraries with Swagger Codegen

To keep things simple, for this example we are going to use the online tool at [editor.swagger.io](https://editor.swagger.io/) to load the `openapi.json` file:

1. Visit [editor.swagger.io](https://editor.swagger.io/) using your web browser.
2. Click on **File > Import File**.
3. Select & upload the `openapi.json` file you've downloaded from the AIoD API.
4. Generate the client for PHP using the **Generate Client > php** menu option.
5. Download the `php-client-generated.zip` file and **save it at the [root of *this* repository](/.)**.

#### 3. Extract the Swagger Codegen client to this repository

1. Make sure that you are at the [root of this project](/.).
2. **Unzip** the contents of the downloaded `php-client-generated.zip` archive:
```shell
unzip php-client-generated.zip
```
After the extraction is complete, the [SwaggerClient-php](./SwaggerClient-php) folder of this project should contain the following sub-directories: `docs`, `lib`, `test`, along with various other files. For this example, only the `lib` folder is required.

#### 4. Install the API clients via Composer

Again, make sure that you are at the [root of this project](/.) and then use **composer** to install the required dependencies and paths.
```shell
composer install
```

### Creating a PHP script to post a News item

Again, we are going to create a News item, only this time the API libraries generated by the Swagger Codegen will be used. You can find the complete example on [api-advanced-example.php](./api-advanced-example.php), which can be run directly via the terminal using the `php api-advanced-example.php` command.

#### 1. Loading the dependencies

For this example, the autoload file from Composer needs to be loaded first. Then, the use statements import the necessary classes from Codegen's generated SDK libraries.
```php
require_once(__DIR__ . '/vendor/autoload.php');

use Swagger\Client\Model\AIoDNews;
use Swagger\Client\Configuration;
```

#### 2. Configuration

A new instance of the SDK's Configuration class is created, which is used to configure the API connection. 
```php
$configuration = new Configuration();
$configuration->setHost('localhost:8000');
```

#### 3. Creating a new API client instance

A new instance of the API client (DefaultApi) provided by the SDK libraries is created. The constructor takes two arguments: an instance of GuzzleHttp\Client, which is used for making HTTP requests, and the previously created $configuration object.
```php
$apiInstance = new Swagger\Client\Api\DefaultApi(
    new GuzzleHttp\Client(),
    $configuration,
);
```

#### 4. Populate an object that represents the news item's data

As with the simple example, this time we need to somehow represent the news item's data. Thanks to the libraries, all we have to do is create a new object and set the data!
```php
$body = new AIoDNews();
// Required fields.
$body->setTitle('Hello World Advanced Edition!');
$body->setHeadline('Hello World! The AIoD API salutes you!');
$body->setSection('Hello World News');
$body->setBody('This is the main body of the advanced news item.');
$body->setDateModified((new DateTime())->format(DateTime::ATOM));
$body->setWordCount(str_word_count($body->getBody()));
// Some optional fields.
$body->setSource('AIoD API Examples: PHP');
$body->setBusinessCategories(['Cloud, Edge and Infrastructure']);
$body->setKeywords(['API', 'Education']);
```

#### 5. Submit the data and get the 'Hello World' response

Finally, the API is called to insert the news item and handle the response:
```php
try {
    $result = $apiInstance->newsNewsV0Post($body);
    print_r($result);
    echo $result->getHeadline() . PHP_EOL;
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->newsNewsV0Post: ', $e->getMessage(), PHP_EOL;
}
```

Even though this approach requires a few additional installation steps the benefits are obvious: increased development efficiency, abstraction of API details, consistency and standardisation, simplified maintenance and reduced chance of errors. 

Enjoy the AIoD API!
