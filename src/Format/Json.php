<?php

namespace Progsmile\Validator\Format;

class Json implements FormatInterface
{
    public function reformat($messages)
    {
        return json_encode($messages);
    }
}
