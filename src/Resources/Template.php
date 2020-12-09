<?php
namespace Avido\Smtpeter\Resources;

class Template extends BaseResource
{
    /** @var int */
    public $id;

    /** @var string */
    public $name;

    public $content;
    public $background;
    public $text;
    public $font;
    public $internals;
    public $from;
    public $subject;
    public $version;

    public function setInternalsAttribute($value)
    {
        $this->internals = $value;
    }
}
