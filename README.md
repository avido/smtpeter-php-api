# Smtpeter PHP Api Client

## Requirements
To use the smtpeter api client, the following things are required:
- A [Copernica account](https://www.copernica.com/)
- Generate your [API Key](https://www.smtpeter.com/nl/app/#/admin)

## Installation
You can install the package using composer.

```
composer require avido/smtpeter-php-api
```

## Getting started
Initialize the Smtpeter php api client and set your API key:

```php
$client = new \Avido\Smtpeter\Client($apiKey);
```

### Templates

#### List all templates
```php
$templates = $client->templates->list();

```
#### Retrieve specific template
```php
$template = $client->template->get($id);
```
