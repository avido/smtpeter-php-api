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

### Emails

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

#### Retrieve email
```php
$messageId = 'abcdef1234';
// by default only the html body is retrieved. 
$loadHtml = true;
$loadAttachments = true;
$loadHeaders = true;
$client->email->get($messageId, $loadHtml, $loadAttachments, $loadHeaders);

```


### Events

#### Get events for specific messageId
```php
$messageId = 'abcdef1234';
$events = $client->events->message($messageId);
// optional you can filter by date/ tags
$from = '2022-01-01';
$end = '2022-01-07';
$filterd = $client->events->message($messageId, $from, $end);
print_r($events);
...
Illuminate\Support\Collection Object
(
    [items:protected] => Array
        (
            [0] => Avido\Smtpeter\Resources\Event Object
             (
                [id] => abcdef1234
                [time] => DateTime Object
                    (
                        [date] => 2022-02-01 08:36:01.000000
                        [timezone_type] => 3
                        [timezone] => UTC
                    )
            
                [recipient] => recipient@domain.tld
                ...
             )
        )
)
```
For more examples see `tests` folder

