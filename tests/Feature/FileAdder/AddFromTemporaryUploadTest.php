<?php

namespace Spatie\Medialibrary\Tests\Feature\FileAdder;

use Illuminate\Http\UploadedFile;
use Spatie\Medialibrary\Models\Media;
use Spatie\Medialibrary\Tests\TestCase;
use Spatie\MedialibraryPro\Models\TemporaryUpload;

class AddFromTemporaryUploadTest extends TestCase
{
    private TemporaryUpload $temporaryUpload;

    public function setUp(): void
    {
        parent::setUp();

        $this->skipIfMedialibraryProNotInstalled();

        $fakeUpload = UploadedFile::fake()->image('test.jpg');
        $this->temporaryUpload = TemporaryUpload::createForFile(
            $fakeUpload,
            session()->getId(),
            'uuid1'
        );
    }

    /** @test */
    public function it_can_a_add_file_from_a_single_temporary_upload()
    {
        $this->testModel
            ->addMediaFromTemporaryUpload($this->temporaryUpload)
            ->toMediaCollection();

        $this->assertCount(1, $this->testModel->getMedia());
        $this->assertCount(0, TemporaryUpload::get());
        $this->assertCount(1, Media::get());

        $this->assertEquals(
            $this->getTestsPath('Support/temp/media/2/test.jpg'),
            $this->testModel->getFirstMediaPath()
        );

        $this->assertEquals('uuid1', $this->testModel->getFirstMedia()->uuid);
    }

    /** @test */
    public function it_can_a_add_file_from_the_uuid_of_a_given_uuid_from_a_media_item_that_belongs_to_a_temporary_upload()
    {
        $this->testModel
            ->addMediaFromTemporaryUpload($this->temporaryUpload->getFirstMedia()->uuid)
            ->toMediaCollection();

        $this->assertCount(1, $this->testModel->getMedia());
        $this->assertCount(0, TemporaryUpload::get());
        $this->assertCount(1, Media::get());

        $this->assertEquals(
            $this->getTestsPath('Support/temp/media/2/test.jpg'),
            $this->testModel->getFirstMediaPath()
        );

        $this->assertEquals('uuid1', $this->testModel->getFirstMedia()->uuid);
    }

    /** @test */
    public function it_can_add_a_file_form_a_temporary_upload_to_a_specific_collection()
    {
        $this->testModel
            ->addMediaFromTemporaryUpload($this->temporaryUpload->getFirstMedia()->uuid)
            ->toMediaCollection('test-collection');

        $this->assertCount(0, $this->testModel->getMedia());
        $this->assertCount(1, $this->testModel->getMedia('test-collection'));
    }

    /** @test */
    public function it_can_add_a_file_form_a_temporary_upload_to_a_specific_disk_and_collection()
    {
        $this->testModel
            ->addMediaFromTemporaryUpload($this->temporaryUpload->getFirstMedia()->uuid)
            ->toMediaCollection('test-collection', 'secondMediaDisk');

        $this->assertEquals(
            $this->getTestsPath('Support/temp/media2/2/test.jpg'),
            $this->testModel->getFirstMediaPath('test-collection')
        );
    }
}
