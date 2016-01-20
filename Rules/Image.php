<?php
namespace Progsmile\Validator\Rules;

class Image extends BaseRule
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
