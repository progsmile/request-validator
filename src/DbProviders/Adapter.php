<?php

namespace Progsmile\Validator\DbProviders;

use Progsmile\Validator\Contracts\Rules\ExistInterface;
use Progsmile\Validator\Contracts\Rules\UniqueInterface;

abstract class Adapter
{
    abstract public function __construct($attribute, $value, $table);
}
