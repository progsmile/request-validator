<?php

namespace Progsmile\Validator\Rules;

use DateTime;

class DateFormat extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $time = $this->getParams()[1];
        $format = trim($this->getParams()[2], '()');

        return $this->respect('Date', [$format])->validate($time);
    }

    /**
     * Returns error message from rule.
     *
     * @return string
     */
    public function getMessage()
    {
        return 'Field :field: has bad date format';
    }
}
