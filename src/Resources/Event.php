<?php
namespace Avido\Smtpeter\Resources;

use Avido\Smtpeter\Support\Str;

class Event extends BaseResource
{
    public $id;
    public $time;
    public $recipient;
    public $templateId;
    public $tags;
    public $envelope;
    public $mime;
    public $properties;
    public $headers;
    public $ip;
    public $url;
    public $destination;
    public $city;
    public $countryname;
    public $countrycode;
    public $regioncode;
    public $from;
    public $to;
    public $attempt;
    public $type;
    public $code;
    public $status;
    public $description;
    public $state;
    public $event;

    public function setHeadersAttribute($value): self
    {
        $this->headers = Str::httpParseHeaders($value);

        return $this;
    }

    public function setTimeAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
            $value->setTimezone(new \DateTimeZone("UTC"));
        }

        $this->time = $value;

        return $this;
    }

    public function setTagsAttribute($value): self
    {
        if (!is_null($value) && trim($value) !== '') {
            $this->tags = explode(";", $value);
        }

        return $this;
    }

    public function toArray(): array
    {
        return collect(parent::toArray())
            ->reject(function ($item) {
                return (is_array($item) && !count($item)) ||
                    is_null($item) ||
                    (is_string($item) && trim($item) === '');
            })
            ->all();
    }
}
