<?php

namespace Progsmile\Validator\Rules;

class Image extends BaseRule
{
    public function isValid()
    {
        //file not required and not send by user
        if ($this->isNotRequiredAndEmpty('file')) {
            return true;
        }

        $fileField = $this->params[0];

        //uploading error: file is too big, or permissions, etc..
        if (!isset($_FILES[$fileField])) {
            return false;
        }

        if ($_FILES[$fileField]['tmp_name']) {

            //if file is image
            return is_array(getimagesize($_FILES[$fileField]['tmp_name']));
        }

        return false;
    }

    public function getMessage()
    {
        return 'Field :field: is not image or there are upload troubles';
    }
}
