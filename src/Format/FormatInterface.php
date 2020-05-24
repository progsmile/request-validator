<?php

namespace Progsmile\Validator\Format;

interface FormatInterface
{
    /**
     * Reformat the message and return the value you want to.
     *
     * @param array $messages
     *
     * @return array|string
     */
    public function reformat($messages);
}
