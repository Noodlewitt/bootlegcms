<?php namespace Bootleg\Cms\Components;

use Illuminate\Auth\Passwords\PasswordBroker as PasswordBrokerBase;
use Illuminate\Contracts\Auth\PasswordBroker as PasswordBrokerContract;

class PasswordBroker extends PasswordBrokerBase implements PasswordBrokerContract {
    public function setEmailView($view) {
        $this->emailView = $view;

        return $this;
    }
}
