<?php
namespace Avido\Smtpeter\Resources;

use Avido\Smtpeter\Support\Str;
use Illuminate\Support\Collection;

class Email extends BaseResource
{
    /** @var Collection  */
    public $recipients;
    /** @var Collection  */
    public $to;
    /** @var string */
    public $from;
    /** @var string */
    public $replyTo;
    /** @var Collection  */
    public $bcc;
    /** @var string */
    public $subject;
    /** @var string */
    public $text;
    /** @var string */
    public $html;
    /** @var int */
    public $templateId;
    /** @var Collection */
    public $attachments;
    /** @var Collection */
    public $headers;
    /** @var array */
    public $data = [];

    public function __construct($attributes = [])
    {
        $this->recipients = new Collection();
        $this->to = new Collection();
        $this->bcc = new Collection();
        $this->attachments = new Collection();
        $this->headers = new Collection();

        parent::__construct($attributes);
    }

    private function addRecipient(string $email): void
    {
        $this->recipients->push(new Recipient(['email' => trim($email)]));
    }

    public function setRecipientAttribute($value): void
    {
        if (!is_null($value)) {
            if (is_array($value)) {
                foreach ($value as $recipient) {
                    $this->setRecipientAttribute($recipient);
                }
            } else {
                if ($value instanceof Recipient) {
                    $this->recipients->push($value);
                } else {
                    if (stristr($value, ",")) {
                        $this->setRecipientAttribute(explode(",", $value));
                    } else {
                        $this->addRecipient(trim($value));
                    }
                }
            }
        }
    }

    public function setToAttribute($value): void
    {
        if (is_array($value)) {
            foreach ($value as $recipient) {
                $this->setToAttribute($recipient);
            }
        } else {
            if ($value instanceof Recipient) {
                $this->to->push($value);
            } else {
                if (stristr($value, ",")) {
                    $this->setToAttribute(explode(",", $value));
                } else {
                    $this->to->push(new Recipient(['email' => trim($value)]));
                    // also add recipient
                    $this->addRecipient(trim($value));
                }
            }
        }
    }

    public function setReplyToAttribute($value): void
    {
        if ($value instanceof Recipient) {
            $this->replyTo = $value;
        } else {
            $this->replyTo = new Recipient(['email' => trim($value)]);
        }
    }

    public function setBccAttribute($value): void
    {
        if (is_array($value)) {
            foreach ($value as $recipient) {
                $this->setBccAttribute($recipient);
            }
        } else {
            if ($value instanceof Recipient) {
                $this->bcc->push($value);
            } else {
                if (stristr($value, ",")) {
                    $this->setBccAttribute(explode(",", $value));
                } else {
                    $this->bcc->push(new Recipient(['email' => trim($value)]));
                    $this->addRecipient(trim($value));
                }
            }
        }
    }

    public function setAttachmentsAttribute($value): self
    {
        if (is_array($value) && count($value)) {
            $this->attachments = collect($value);
        }

        return $this;
    }

    public function setHeadersAttribute($value): self
    {
        if (trim($value) !== '') {
            $this->headers = collect(Str::httpParseHeaders($value));
            // get recipient / subject info from headers.
            $this->subject = $this->headers->get('Subject');
            $this->setToAttribute($this->headers->get('To'));
            $this->from = $this->headers->get('From');
        }

        return $this;
    }

    public function toArray(): array
    {
        return collect(parent::toArray())
            ->when(!is_null($this->templateId), function ($collection) {
                $collection->put('template', $this->templateId)
                    ->forget('templateId');

                return $collection;
            })
            ->when($this->recipients->count(), function ($collection) {
                $recipients = $this->recipients
                    ->map(function ($item) {
                        return $item->email;
                    })
                    ->all();

                return $collection
                    ->put('recipients', $recipients);
            })
            // fallback when only receivers is set
            ->when(!$this->to->count(), function ($collection) {
                $recipients = $this->recipients
                    ->map(function ($item) {
                        return $item->email;
                    })
                    ->all();

                return $collection
                    ->put('to', $recipients);
            })
            ->when($this->to->count(), function ($collection) {
                $to = $this->to
                    ->map(function ($item) {
                        return $item->email;
                    })
                    ->all();

                return $collection
                    ->put('to', $to);
            })
            ->when(!is_null($this->replyTo), function ($collection) {
                return $collection
                    ->put('replyTo', $this->replyTo->email);
            })
            ->when($this->bcc->count(), function ($collection) {
                $recipients = $this->bcc
                    ->map(function ($item) {
                        return $item->email;
                    })
                    ->all();

                return $collection
                    ->put('bcc', $recipients);
            })
            ->when($this->attachments->count(), function ($collection) {
                return $collection->put('attachments', $this->attachments->toArray());
            })
            ->when($this->headers->count(), function ($collection) {
                return $collection->put('headers', $this->headers->toArray());
            })
            ->reject(function ($value) {
                return (is_array($value) && !count($value))
                    || ($value instanceof Collection && !$value->count())
                    || is_null($value);
            })
            ->all();
    }
}
