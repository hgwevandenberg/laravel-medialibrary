<?php

namespace Spatie\Medialibrary\Helpers;

use Spatie\Medialibrary\Exceptions\FunctionalityNotAvailable;
use Spatie\MedialibraryPro\Models\TemporaryUpload;

class Util
{
    public static function ensureMedialibraryProInstalled()
    {
        if (! class_exists(TemporaryUpload::class)) {
            throw FunctionalityNotAvailable::medialibraryProRequired();
        }
    }

    public static function medialibraryProInstalled(): bool
    {
        return class_exists(TemporaryUpload::class);
    }
}
