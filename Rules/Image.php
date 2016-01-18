<?php
namespace Progsmile\Validator\Rules;

use Progsmile\Validator\Contracts\Rules\RulesInterface;

class Image extends BaseRule implements RulesInterface
{
    public function isValid()
    {
        $uploadedFile = $_FILES[$this->params[0]];

        return $uploadedFile['tmp_name'] && is_array(getimagesize($uploadedFile['tmp_name']));
    }

    public function getMessage()
    {
        return 'Field :field: is not image.';
    }
}
