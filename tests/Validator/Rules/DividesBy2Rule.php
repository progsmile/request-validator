<?php

namespace Tests\Validator\Rules;

use Progsmile\Validator\Rules\BaseRule;

class DividesBy2Rule extends BaseRule {

    public function getMessage()
    {
        return 'Can\'t divide :field: by 2';
    }

    public function isValid()
    {
        $value = $this->getParams()[1];

        return $value % 2 === 0;
    }
}
