<?php

namespace Spatie\Medialibrary\Exceptions;

use Exception;

class FunctionalityNotAvailable extends Exception
{
    public static function medialibraryProRequired()
    {
        return new static("You need to have medialibrary pro installed to make this work.");
    }
}
