<?php
namespace Avido\Smtpeter\Resources;

class DeliveryStatusNotification extends BaseResource
{
    private $availbleNotificationTypes = ['NEVER', 'FAILURE', 'SUCCESS', 'DELAY'];

    public $notify;
    public $ret;
    public $envid;
    public $orcpt;

    public function setNotifyAttribute($value)
    {
        if (!in_array($value, $this->availbleNotificationTypes)) {
            throw new \InvalidArgumentException(
                "Invalid notification type, available types are: " .
                implode(",", $this->availbleNotificationTypes)
            );
        }
        $this->notify = $value;
    }
}
