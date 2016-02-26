<?php

namespace Progsmile\Validator\Format;

use Progsmile\Validator\Contracts\Format\FormatInterface;

class Json implements FormatInterface
{
    public function reformat($messages)
    {
        return json_encode($messages);
    }
}
