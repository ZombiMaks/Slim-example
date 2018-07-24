<?php

namespace App;

class Validator implements ValidatorInterface
{
    public function validate(array $user)
    {
        // BEGIN (write your solution here)
        $errors = [];
        if (empty($user['name'])) {
            $errors['name'] = "Can't be blank";
        }elseif(empty($user['email'])){
            $errors['email'] = "Can't be blank";
        }
        return $errors; 
        // END
    }
}