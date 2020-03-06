<?php

namespace Spatie\MediaLibrary\MediaCollections;

use Illuminate\Contracts\Support\Htmlable;
use Spatie\MediaLibrary\Conversions\ConversionCollection;
use Spatie\MediaLibrary\Conversions\ImageGenerators\Image;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class HtmlableMedia implements Htmlable
{
    protected Media $media;

    protected string $conversionName = '';

    protected array $extraAttributes = [];

    protected string $loadingAttributeValue = '';

    public function __construct(Media $media)
    {
        $this->media = $media;
    }

    public function extraAttributes(array $extraAttributes): self
    {
        $this->extraAttributes = $extraAttributes;

        return $this;
    }

    public function conversion(string $conversionName): self
    {
        $this->conversionName = $conversionName;

        return $this;
    }

    public function loading(string $loadingAttributeValue): self
    {
        $this->loadingAttributeValue = $loadingAttributeValue;

        return $this;
    }

    public function lazy(): self
    {
        return $this->loading('lazy');
    }

    public function toHtml()
    {
        if (! (new Image())->canHandleMime($this->media->mime_type)) {
            return '';
        }

        $attributeString = collect($this->extraAttributes)
            ->map(fn($value, $name) => $name.'="'.$value.'"')->implode(' ');

        if (strlen($attributeString)) {
            $attributeString = ' '.$attributeString;
        }

        $loadingAttributeValue = config('media-library.default_loading_attribute_value');

        if ($this->conversionName !== '') {
            $conversionObject = ConversionCollection::createForMedia($this->media)->getByName($this->conversionName);

            $loadingAttributeValue = $conversionObject->getLoadingAttributeValue();
        }

        if ($this->loadingAttributeValue !== '') {
            $loadingAttributeValue = $this->loadingAttributeValue;
        }

        $media = $this;

        $viewName = 'image';

        $width = '';

        if ($this->media->hasResponsiveImages($this->conversionName)) {
            $viewName = config('media-library.responsive_images.use_tiny_placeholders')
                ? 'responsiveImageWithPlaceholder'
                : 'responsiveImage';

            $width = $this->media->responsiveImages($this->conversionName)->files->first()->width();
        }

        $media = $this->media;
        $conversion = $this->conversionName;

        return view("media-library::{$viewName}", compact(
            'media',
            'conversion',
            'attributeString',
            'loadingAttributeValue',
            'width',
        ))->render();
    }

    public function __toString()
    {
        return $this->toHtml();
    }
}
