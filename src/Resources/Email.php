<?php
namespace Avido\Smtpeter\Resources;

class Email extends BaseResource
{
    /** @var array  */
    public $recipients = [];
    /** @var string */
    public $from;
    /** @var string */
    public $replyto;
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

    public function setRecipientAttribute($value)
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
                    $this->recipients[] = new Recipient(['email' => trim($value)]);
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
                    ->put('recipients', $recipients)
                    ->put('to', $recipients);
            })
            ->reject(function ($value) {
                return (is_array($value) && !count($value));
            })
            ->all();
    }
}
