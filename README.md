# AIoD API Examples: PHP

This repository contains a few easy to follow examples build in PHP, which consume the AI-on-Demand API ([AIoD API](https://github.com/aiondemand/AIOD-rest-api) version [0.3.20220501](https://github.com/aiondemand/AIOD-rest-api/releases/tag/0.3.20220501)) to send and/or retrieve data. 

## Prerequisites

- Basic understanding of the PHP coding language
- A local development environment which allows execution of PHP code
- [Docker](https://www.docker.com/) to install & run the AI-on-Demand API ([AIoD API](https://github.com/aiondemand/AIOD-rest-api))
- [Composer](https://getcomposer.org/) for the *advanced* examples

---

## Getting started

### Clone the 'AIoD API Examples: PHP' repository

Clone this repository to your local machine by running the following command:
```shell
git clone git@github.com:aiondemand/aiod-api-examples-php.git
```

An `aiod-api-examples-php` folder will be created with the contents of this repository. Do not change to that folder, as you also have to clone, install & then run the AIoD API repository.

### Clone, install & run the AIoD API repository

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

At this point you should be able to access the API using your web browser at [localhost:8000](http://localhost:8000). This instance operates at your own machine independently of the actual AIoD API, ensuring that real data is not accidentally modified or corrupted. In other words, it's the ideal playground for development or testing!

---

## Examples

- [Simple "Hello World!" example: Using PHP's cURL to insert a News item](./examples/simple/README.md): This example demonstrates how easy it is to store information on the AIoD API using PHP's built-in cURL.
- [Advanced "Hello World!" example: Using API client libraries to insert a News item](./examples/advanced/README.md): The integration process with the AIoDP API can be greatly simplified and streamlined with the utilisation of SDK libraries created automatically via Swagger Codegen.

---

Enjoy the AIoD API!
