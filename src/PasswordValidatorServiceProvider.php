<?php

namespace Hshafiei374\PasswordValidator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class PasswordValidatorServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function boot()
    {
        Validator::resolver(function ($translator, $data, $rules, $messages = [], $customAttributes = []) {
            return new PasswordValidator($translator, $data, $rules, $messages, $customAttributes);
        });
    }

}
