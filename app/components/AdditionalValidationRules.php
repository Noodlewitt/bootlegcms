<?php

class AdditionalValidationRules extends \Illuminate\Validation\Validator {
    public function validatePhone($attribute, $value, $parameters){
        return preg_match("/^([0-9\s\-\+\(\)]*)$/", $value);
    }

    public function validateGreater($attribute, $value, $parameters){
        if($parameters[0] < $value){
            return true;
        }
        else return false;
    }
}