<?php
namespace Avido\Smtpeter\Resources;


class Recipient extends BaseResource
{
    /** @var string */
    public $id;
    /** @var string */
    public $email;

    public function toArray(): array
    {
        return collect(parent::toArray())
            ->all();
    }
}
