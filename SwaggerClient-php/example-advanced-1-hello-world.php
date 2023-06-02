<?php

require_once(__DIR__ . '/vendor/autoload.php');

use Swagger\Client\Model\AIoDOrganisation;

$apiInstance = new Swagger\Client\Api\DefaultApi(
    new GuzzleHttp\Client()
);

$schema = "aiod"; // string |
$offset = 0; // int |
$limit = 100; // int |
$identifier = "identifier_example"; // string |

// 1. Let's build the object that represents the basic organization's data:
$body = new AIoDOrganisation();
$body->setName('Hello World Organisation');
$body->setDescription('Hello World! The AIoD API salutes you!');
$body->setType('Education Institution');
$body->setBusinessCategories(['Cloud, Edge and Infrastructure']);
$body->setTechnicalCategories(['Knowledge Representation']);

// 2. Submit the object to the API!
try {
    $result = $apiInstance->organisationOrganisationsV0IdentifierPut($body, $identifier);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->organisationOrganisationsV0IdentifierPut: ', $e->getMessage(), PHP_EOL;
}
