<?php
namespace Avido\Smtpeter\Resources;


class EmailOptions extends BaseResource
{
    /** @var boolean */
    public $inlinecss;
    /** @var boolean */
    public $trackclicks;
    /** @var boolean */
    public $trackopens;
    /** @var boolean */
    public $trackbounces;
    /** @var boolean */
    public $preventscam;
    /** @var array  */
    public $tags = [];
    /** @var \DateTime (utc)  */
    public $maxdelivertime;
    /** @var Dsn */
    public $dsn;

    public function setDsnAttribute($value)
    {
        if (!$value instanceof DeliveryStatusNotification) {
            $value = new DeliveryStatusNotification($value);
        }
        $this->dsn = $value;
    }

    public function toArray(): array
    {
        return collect(parent::toArray())
            ->reject(function ($value) {
                return empty($value);
            })
            ->all();
    }
}
