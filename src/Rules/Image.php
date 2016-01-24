<?php
namespace Progsmile\Validator\Rules;

class Image extends BaseRule
{
    public function isValid()
    {
        //server errors while uploading
        if(!isset($_FILES[$this->params[0]]) || !empty($_FILES) && $_FILES[$this->params[0]]['error'] != 4){
            return false;
        }

        //file not required and not uploaded
        if( $this->isNotRequiredAndEmpty('file') ){
            return true;
        }

        $uploadedFile = $_FILES[$this->params[0]];

        //if file is image
        return is_array(@getimagesize($uploadedFile['tmp_name']));
    }

    public function getMessage()
    {
        return 'Field :field: is not image or there are upload troubles.';
    }
}
