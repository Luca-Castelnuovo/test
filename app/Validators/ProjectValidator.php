<?php

namespace App\Validators;

use Respect\Validation\Validator as v;

class ProjectValidator extends ValidatorBase
{
    /**
     * Validate json submission
     *
     * @param object $data
     *
     * @return void
     */
    public static function create($data)
    {
        $v = v::attribute('name', v::alnum(' ', '_', '-'));

        ValidatorBase::validate($v, $data);
    }
}
