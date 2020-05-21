<?php

namespace App\Validators;

use CQ\Validators\Validator;
use Respect\Validation\Validator as v;

class ProjectValidator extends Validator
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

        self::validate($v, $data);
    }
}
