<?php
namespace Progsmile\Validator\Rules;

class Url extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()){
            return true;
        }

        $url = trim($this->params[1]);

        if ($parts = parse_url($url)){
            if ( !isset($parts['scheme'])){
                $url = 'http://' . $url;
            }
        }

        return substr_count($url, '.') >= 1 && filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    public function getMessage()
    {
        return 'Field :field: is not URL.';
    }
}
