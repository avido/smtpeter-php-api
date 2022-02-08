# Smtpeter PHP Api Client

This release is not production ready yet.. use at own risk!


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
Templates list results can be limit with an `offset` and `limit` variable 
```php
$templates = $client->templates->list();
$templatesWithLimit = $client->templates->list($offset, $limit);
```

#### Retrieve specific template
```php
$template = $client->template->get($id);
```

#### Send out email using template
```php
// simple email
$email = new Email([
    'templateId' => 1,
    'to' => 'receiver@domain.tld',
    'data' => ['array' => 'of replacement vars']
]);
// email with bcc
$email = new Email([
    'templateId' => 1,
    'to' => 'receiver@domain.tld',
    'bcc' => 'bcc@domain.tld',
    'data' => ['array' => 'of replacement vars']
]);
// email with replyTo address
$email = new Email([
    'templateId' => 1,
    'to' => 'receiver@domain.tld',
    'replyTo' => 'replyTo@domain.tld',
    'data' => ['array' => 'of replacement vars']
]);

$client->email->send($email);
```

#### Resend email
```php
$messageId = 'abcdef1234';
$client->email->resend($messageId   );
```
