<?php

/* Advanced example: Insert an AI Asset to the AIoD API using SDK libraries.
 *
 * Before running this script make sure you've created the Swagger Codegen
 * libraries and placed them under SwaggerClient-php. After that, you should run
 * 'composer install' on the root of this project.
 *
 * As long as PHP and the AIoD API is installed on your machine, you can run
 * this code by running "php api-advanced-example.php" on your terminal. The
 * expected result is "Hello World!", posted & retrieved via the API!
 */

# Loading dependencies.
require_once(__DIR__ . '/vendor/autoload.php');

use Swagger\Client\Model\AIoDNews;
use Swagger\Client\Configuration;

# Configuration.
$configuration = new Configuration();
$configuration->setHost('localhost:8000');

# New API instance.
$apiInstance = new Swagger\Client\Api\DefaultApi(
    new GuzzleHttp\Client(),
    $configuration,
);

# Populates an object that represents the news item's data:
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

# Submits the data and gets the Hello World response:
try {
    $result = $apiInstance->newsNewsV0Post($body);
    print_r($result);
    echo $result->getHeadline() . PHP_EOL;
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->newsNewsV0Post: ', $e->getMessage(), PHP_EOL;
}
