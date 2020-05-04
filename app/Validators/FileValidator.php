<?php

namespace App\Validators;

use Respect\Validation\Validator as v;

class FileValidator extends ValidatorBase
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
        $v = v::attribute('name', v::stringType());

        ValidatorBase::validate($v, $data);
    }

    /**
     * Validate json submission
     *
     * @param object $data
     *
     * @return void
     */
    public static function update($data)
    {
        $v = v::attribute('content', v::stringType())
            ->attribute('quit', v::boolType()); // TODO: correct validation

        ValidatorBase::validate($v, $data);
    }
}
