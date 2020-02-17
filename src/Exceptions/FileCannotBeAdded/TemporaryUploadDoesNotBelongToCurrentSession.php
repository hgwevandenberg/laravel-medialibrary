<?php

namespace Spatie\Medialibrary\Exceptions\FileCannotBeAdded;

use Exception;
use Spatie\MedialibraryPro\Models\TemporaryUpload;

class TemporaryUploadDoesNotBelongToCurrentSession extends Exception
{
    public static function create(TemporaryUpload $temporaryUpload): self
    {
        return new static('The the session id of the given temporary upload does not match the curren session id');
    }
}
