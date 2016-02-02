<?php
namespace Progsmile\Validator\Rules;

class DateFormat extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()){
            return true;
        }

        $dateTime   = $this->params[1];
        $dateFormat = trim($this->params[2], '()');

        $d = \DateTime::createFromFormat($dateFormat, $dateTime);

        return $d && $d->format($dateFormat) == $dateTime;
    }

    /**
     * Returns error message from rule
     *
     * @return string
     */
    public function getMessage()
    {
        return 'Field :field: has bad date format';
    }
}
