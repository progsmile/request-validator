<?php

namespace Tests\Validator\Rules;

use Progsmile\Validator\Rules\BaseRule;

class AlwaysFailRule extends BaseRule {

    public function getMessage()
    {
        return 'Your :field: is invalid';
    }

    public function isValid()
    {
        // use `$this->getParams()` to get values

        return false;
    }
}
