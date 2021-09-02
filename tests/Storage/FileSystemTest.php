<?php

declare(strict_types=1);

namespace Platine\Test\Upload\Storage;

use InvalidArgumentException;
use org\bovigo\vfs\vfsStream;
use Platine\Dev\PlatineTestCase;
use Platine\Upload\Exception\StorageException;
use Platine\Upload\Exception\UploadException;
use Platine\Upload\File\File;
use Platine\Upload\File\UploadFileInfo;
use Platine\Upload\Storage\FileSystem;

/**
 * FileSystem class tests
 *
 * @group core
 * @group upload
 * @group storage
 */
class FileSystemTest extends PlatineTestCase
{

    protected $vfsRoot;
    protected $vfsFilePath;
    protected function setUp(): void
    {
        parent::setUp();
    //need setup for each test
        $this->vfsRoot = vfsStream::setup();
        $this->vfsFilePath = vfsStream::newDirectory('tests')->at($this->vfsRoot);
    }

    public function testConstructorSuccess(): void
    {
        global $mock_realpath;
        $mock_realpath = true;
        $f = new FileSystem($this->vfsFilePath->url(), true);
        $this->assertTrue($this->getPropertyValue(FileSystem::class, $f, 'overwrite'));
        $path = $this->getPropertyValue(FileSystem::class, $f, 'path');
        $this->assertEquals($this->vfsFilePath->url() . DIRECTORY_SEPARATOR, $path);
    }

    public function testConstructorPathNotExist(): void
    {
         global $mock_realpath;
        $mock_realpath = true;
        $this->expectException(InvalidArgumentException::class);
        $f = new FileSystem('not_found', true);
    }

    public function testConstructorPathNotWritable(): void
    {
        $this->expectException(InvalidArgumentException::class);
        chmod($this->vfsFilePath->url(), 100);
        $f = new FileSystem($this->vfsFilePath->url(), true);
    }

    public function testUploadFileAlreadyExist(): void
    {
        global $mock_file_exists_true, $mock_realpath;
        $mock_realpath = true;
        $mock_file_exists_true = true;
        $file = $this->getMockInstance(File::class);
        $f = new FileSystem($this->vfsFilePath->url(), false);
        $this->expectException(StorageException::class);
        $f->upload($file);
    }

    public function testUploadError(): void
    {
        global $mock_copy_false, $mock_realpath;
        $mock_copy_false = true;
        $mock_realpath = true;
        $file = $this->getMockInstance(File::class);
        $f = new FileSystem($this->vfsFilePath->url(), true);
        $this->expectException(UploadException::class);
        $f->upload($file);
    }

    public function testUploadSuccess(): void
    {
        global $mock_copy_true, $mock_realpath;
        $mock_copy_true = true;
        $mock_realpath = true;
        $file = $this->getMockInstance(File::class);
        $f = new FileSystem($this->vfsFilePath->url(), true);
        $res = $f->upload($file);
        $this->assertInstanceOf(UploadFileInfo::class, $res);
    }
}
