<?php

namespace Progsmile\Validator\DbProviders;

abstract class Adapter
{
    abstract public function __construct($attribute, $value, $table);
}
