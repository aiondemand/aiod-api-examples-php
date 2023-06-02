# AIoD API Examples: PHP

This repository contains various examples build in PHP, which utilise the AI-on-Demand API ([AIoD API](https://github.com/aiondemand/AIOD-rest-api)). Following the examples provided in this repository you will be able to build your own app in PHP that access the API, retrieves and posts data.

## Prerequisites

- Basic understanding of the PHP coding language.
- A local development environment which allows the execution of PHP code:
  - On Linux you can install PHP with `sudo apt-get install php`
  - On macOS, you can use `brew install php`
  - On Windows you can download and install PHP from [php.net](https://windows.php.net/).
- [Docker](https://www.docker.com/) in order to install and run the AI-on-Demand API ([AIoD API](https://github.com/aiondemand/AIOD-rest-api)) version [0.3.20220501](https://github.com/aiondemand/AIOD-rest-api/releases/tag/0.3.20220501).
- [Composer](https://getcomposer.org/) for the *advanced* example.

## Installation of the AIoDP API

Using git, you should start by cloning the [AIoD API repository](https://github.com/aiondemand/AIOD-rest-api). This command will create an `AIOD-rest-api` folder and clone the files from the repository to your local machine:

```shell
git clone git@github.com:aiondemand/AIOD-rest-api.git
```

As these instructions were based on version 0.3.20220501 of the API make sure that you've switched to that version:

```shell
git checkout tags/0.3.20220501 -b 0.3.20220501
```

Go ahead and change to the newly created folder:

```shell
cd AIOD-rest-api
```

Make sure that Docker is running on your machine and then follow the [installation instructions](https://github.com/aiondemand/AIOD-rest-api/blob/develop/README.md#installation) provided on the AIoDP API repository. Start by using `docker` to create a "network", to start the MySQL server, to build the docker image and finally to connect all of these services together.

```shell
docker network create sql-network
docker run -e MYSQL_ROOT_PASSWORD=ok --name sqlserver --network sql-network -d mysql
docker build --tag ai4eu_server_demo:latest -f Dockerfile .
docker run --network sql-network -it -p 8000:8000 --name apiserver ai4eu_server_demo
```

## Basic example: Hello World!

At this point you should be able to access the local API at [localhost:8000/docs](http://localhost:8000/docs). Of course, this instance operates at your own machine independently of the actual AIoD API, ensuring that real data is not accidentally modified or corrupted. In other words, it's the ideal playground for development or testing!

So, let's create some code that will allow posting and retrieving the "Hello World!" string via the AIoD API. The final result can be found on [example-basic-1-hello-world.php](./example-basic-1-hello-world.php) and can be run directly on any terminal using the `php example-basic-1-hello-world.php` command.

To create a new organisation, we need an array of data that represents the organisation. The data structure is provided under each endpoint at the [localhost:8000/docs](http://localhost:8000/docs). To keep things simple, we are going to fill-in only the required fields (and just a few of the optional ones):

### Creating a "Hello World Organisation"

```php
$data = [
  "name" => "Hello World Organisation",
  "description" => "Hello World! The AIoD API salutes you!",
  "type" => "Education Institution",
  "businessCategories" => ["Cloud, Edge and Infrastructure"],
  "technicalCategories" => ["Knowledge Representation"]
];
```

Of course, this array can't be posted directly to the API. It has to be converted to JSON:
```php
$json_data = json_encode($data);
```

Then, the URL of the endpoint has to be defined:
```php
$url = 'http://localhost:8000/organisations/v0';
```

And cURL has to be used to send the JSON data to the API:
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
$response = curl_exec($curl);
```

And that's basically all it takes to post the Organisation to the API!

... But, we are still missing a 'Hello World!' response, so let's try to get it! The `$response` from the API can be decoded from JSON to an associative array using `json_decode($response, true)`, and from that, we can extract the description and display it on screen using a simple good-old`echo`:

```php 
$response_data = json_decode($response, true);
$description = $response_data['description'];
echo $description;
```

By now, you have learned how to use PHP and cURL to send data to the API and to even do some basic handling of the responses! Again, you are invited to take a closer look at the complete example provided at [example-basic-1-hello-world.php](./example-basic-1-hello-world.php), run it on your own machine and experiment with the data and the responses you can send and retrieve from the API!

## Advanced example: Creating & using API client libraries with Swagger Codegen

### 1. Create the API client libraries

The AIoD API follows the [OpenAPI](https://github.com/OAI/OpenAPI-Specification) specification, and therefore it's extremely easy to build libraries for accessing its endpoints in order to store and/or retrieve data. In fact, this can be done automatically with the help of [Swagger Codegen](https://github.com/swagger-api/swagger-codegen), a project which allows generation of API client libraries (SDK generation), server stubs and documentation automatically given an OpenAPI Spec.

You can find the OpenAPI Spec for your local installation of the AIoD API on [localhost:8000/openapi.json](http://localhost:8000/openapi.json). From there, you can simply follow the instructions on the Swagger Codegen project to build the required libraries, or use the tool at 
[editor.swagger.io](https://editor.swagger.io/) to load the `openapi.json` file (via the File > Import File menu) and then to generate the client for PHP (via Generate Client > php).

### 2.  Move the client to the SwaggerClient-php folder of this repository

After building the `SwaggerClient-php` libraries, please move **all its contents** (directories `docs`, `lib`, `test`, along with the `composer.json`, `composer.lock` & other files to the [SwaggerClient-php](./SwaggerClient-php) folder of this repository.

### 3. Install the API clients via Composer

After you have moved the files to the [SwaggerClient-php](./SwaggerClient-php) folder of this repository use your terminal to install the required dependencies:

```shell
composer install
```

### 4. Creating a "Hello World Organisation" using the API libraries

Again, we are going to create a new Organisation, only this time the API libraries generated by the Swagger Codegen will be used instead. You can find the complete example on [example-advanced-1-hello-world.php](./SwaggerClient-php/example-advanced-1-hello-world.php).

As with the basic example, this time we need to somehow represent the organisation's data. Thanks to the libraries, all we have to do is create a new object and fill it!

```php
$body = new AIoDOrganisation();
$body->setName('Hello World Organisation');
$body->setDescription('Hello World! The AIoD API salutes you!');
$body->setType('Education Institution');
$body->setBusinessCategories(['Cloud, Edge and Infrastructure']);
$body->setTechnicalCategories(['Knowledge Representation']);
```

The second step is to... submit the data:

```php
try {
    $result = $apiInstance->organisationOrganisationsV0IdentifierPut($body, $identifier);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->organisationOrganisationsV0IdentifierPut: ', $e->getMessage(), PHP_EOL;
}
```

As you can see, this is even easier than the... basic example, thanks to the libraries that the Swagger Codegen has generated. Feel free to take a look at the complete example at [example-advanced-1-hello-world.php](./SwaggerClient-php/example-advanced-1-hello-world.php), run it on your own machine and experiment! 

Enjoy the AIoD API!



