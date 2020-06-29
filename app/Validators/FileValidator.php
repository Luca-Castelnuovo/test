<?php

namespace App\Validators;

use CQ\Validators\Validator;
use Respect\Validation\Validator as v;

class FileValidator extends Validator
{
    /**
     * Validate json submission.
     *
     * @param object $data
     */
    public static function create($data)
    {
        $v = v::attribute('name', v::stringType());

        self::validate($v, $data);
    }

    /**
     * Validate json submission.
     *
     * @param object $data
     */
    public static function update($data)
    {
        $v = v::attribute('content', v::stringType())
            ->attribute('quit', v::boolType())
        ;

        self::validate($v, $data);
    }
}
