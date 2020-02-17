<?php

namespace Spatie\Medialibrary\Exceptions\FileCannotBeAdded;

use Spatie\Medialibrary\Exceptions\FileCannotBeAdded;

class UnknownType extends FileCannotBeAdded
{
    public static function create(): self
    {
        return new static('Only strings, FileObjects, UploadedFileObjects and TemporaryUploads can be imported');
    }
}
