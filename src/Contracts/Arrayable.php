<?php
namespace Avido\Smtpeter\Contracts;

interface Arrayable
{
    public function toJson(int $options = 0): string;
}
