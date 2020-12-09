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

    /**
     * Extract variables from template
     * Note, This is not bullet proof.
     * @return array
     */
    public function getVariables(): array
    {
        if (!is_null($this->content)) {
            preg_match_all('/{\$(.+?)}/i', $this->content, $matches);
            if (isset($matches[1])) {
                return collect($matches[1])
                    ->map(function ($item) {
                        if (strpos($item, "|")) {
                            return substr($item, 0, strpos($item, "|"));
                        } else {
                            return $item;
                        }
                    })
                    ->all();
            }
        }
        return [];
    }
}
