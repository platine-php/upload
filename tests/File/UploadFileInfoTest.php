<?php

declare(strict_types=1);

namespace Platine\Test\Upload\File;

use org\bovigo\vfs\vfsStream;
use Platine\Dev\PlatineTestCase;
use Platine\Upload\File\UploadFileInfo;

/**
 * UploadFileInfo class tests
 *
 * @group core
 * @group upload
 */
class UploadFileInfoTest extends PlatineTestCase
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

    public function testAll(): void
    {
        $file = $this->createVfsFile('test.txt', $this->vfsFilePath, 'foo');
        $f = new UploadFileInfo($file->url(), 'text/plain', 5, 289, 'checksumvalue', 'client_name');
        $this->assertEquals(5, $f->getError());
        $this->assertEquals(289, $f->getSize());
        $this->assertEquals('text/plain', $f->getMimeType());
        $this->assertEquals('txt', $f->getExtension());
        $this->assertEquals('test', $f->getName());
        $this->assertEquals('checksumvalue', $f->getChecksum());
        $this->assertEquals('client_name', $f->getClientName());
        $this->assertEquals('test.txt', $f->getFullName());
        $this->assertEquals($this->vfsFilePath->url() . '/test.txt', $f->getPath());
    }
}
