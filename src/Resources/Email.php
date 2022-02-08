<?php
namespace Avido\Smtpeter\Resources;

class Email extends BaseResource
{
    /** @var array  */
    public $recipients = [];
    /** @var array  */
    public $to = [];
    /** @var string */
    public $from;
    /** @var string */
    public $replyTo;
    /** @var array  */
    public $bcc = [];
    /** @var string */
    public $subject;
    /** @var string */
    public $text;
    /** @var string */
    public $html;
    /** @var int */
    public $templateId;
    /** @var array */
    public $data = [];

    private function addRecipient(string $email): void
    {
        $this->recipients[] = new Recipient(['email' => trim($email)]);
    }

    public function setRecipientAttribute($value): void
    {
        if (is_array($value)) {
            foreach ($value as $recipient) {
                $this->setRecipientAttribute($recipient);
            }
        } else {
            if ($value instanceof Recipient) {
                $this->recipients[] = $value;
            } else {
                if (stristr($value, ",")) {
                    $this->setRecipientAttribute(explode(",", $value));
                } else {
                    $this->addRecipient(trim($value));
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
                $this->to[] = $value;
            } else {
                if (stristr($value, ",")) {
                    $this->setToAttribute(explode(",", $value));
                } else {
                    $this->to[] = new Recipient(['email' => trim($value)]);
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
                $this->bcc[] = $value;
            } else {
                if (stristr($value, ",")) {
                    $this->setBccAttribute(explode(",", $value));
                } else {
                    $this->bcc[] = new Recipient(['email' => trim($value)]);
                    $this->addRecipient(trim($value));
                }
            }
        }
    }

    public function toArray(): array
    {
        return collect(parent::toArray())
            ->when(!is_null($this->templateId), function ($collection) {
                $collection->put('template', $this->templateId)
                    ->forget('templateId');

                return $collection;
            })
            ->when(count($this->recipients), function ($collection) {
                $recipients = collect($this->recipients)
                    ->map(function ($item) {
                        return $item->email;
                    })
                    ->all();

                return $collection
                    ->put('recipients', $recipients);
            })
            // fallback when only receivers is set
            ->when(!count($this->to), function ($collection) {
                $recipients = collect($this->recipients)
                    ->map(function ($item) {
                        return $item->email;
                    })
                    ->all();

                return $collection
                    ->put('to', $recipients);
            })
            ->when(count($this->to), function ($collection) {
                $to = collect($this->to)
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
            ->when(count($this->bcc), function ($collection) {
                $recipients = collect($this->bcc)
                    ->map(function ($item) {
                        return $item->email;
                    })
                    ->all();

                return $collection
                    ->put('bcc', $recipients);
            })
            ->reject(function ($value) {
                return (is_array($value) && !count($value));
            })
            ->all();
    }
}
