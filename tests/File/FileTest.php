<?php

declare(strict_types=1);

namespace Platine\Test\Upload\File;

use org\bovigo\vfs\vfsStream;
use Platine\Dev\PlatineTestCase;
use Platine\Upload\File\File;
use RuntimeException;

/**
 * File class tests
 *
 * @group core
 * @group upload
 */
class FileTest extends PlatineTestCase
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

    protected function tearDown(): void
    {
        parent::tearDown();
//restore factory
        File::setFactory(null);
    }

    public function testConstructor(): void
    {
        $file = $this->createVfsFile('test.txt', $this->vfsFilePath, 'foo');
        $f = new File($file->url());
        $this->assertEquals(UPLOAD_ERR_OK, $f->getError());
        $this->assertEquals(3, $f->getSize());
        $this->assertEquals('text/plain', $f->getMimeType());
        $this->assertEquals('txt', $f->getExtension());
        $this->assertEquals('test', $f->getName());
        $this->assertEquals('test.txt', $f->getFullName());
        $this->assertEquals($this->vfsFilePath->url() . '/test.txt', $f->getPathname());
    }

    public function testCreateNoFactory(): void
    {
        $file = $this->createVfsFile('test.txt', $this->vfsFilePath, 'foobar');
        $f = File::create($file->url());
        $this->assertEquals(UPLOAD_ERR_OK, $f->getError());
        $this->assertEquals(6, $f->getSize());
        $this->assertEquals('txt', $f->getExtension());
        $this->assertEquals('test', $f->getName());
        $this->assertEquals('test.txt', $f->getFullName());
    }

    public function testCreateUsingFactory(): void
    {
        $file = $this->createVfsFile('test.txt', $this->vfsFilePath, 'foobar');
        File::setFactory(function ($tmpName, $clientName = '', $name = null, int $error) {

            $f = new File($tmpName, $clientName, $name, $error);
            $f->setName('factory');
            return $f;
        });
        $f = File::create($file->url());
        $this->assertEquals(UPLOAD_ERR_OK, $f->getError());
        $this->assertEquals('factory', $f->getName());
        $this->assertEquals('', $f->getClientName());
    }

    public function testCreateUsingFactoryWrongReturnType(): void
    {
        $file = $this->createVfsFile('test.txt', $this->vfsFilePath, 'foobar');
        File::setFactory(function ($tmpName, $clientName = '', $name = null, int $error) {

            return 'foobar';
        });
        $this->expectException(RuntimeException::class);
        $f = File::create($file->url());
    }

    public function testGetFullNameEmptyExtension(): void
    {
        $file = $this->createVfsFile('test', $this->vfsFilePath, 'foo');
        $f = new File($file->url());
        $this->assertEmpty($f->getExtension());
        $this->assertEquals('test', $f->getFullName());
    }

    public function testGetMD5ReturnFalse(): void
    {
        global $mock_md5_file_to_false;
        $mock_md5_file_to_false = true;
        $file = $this->createVfsFile('test', $this->vfsFilePath, 'foo');
        $f = new File($file->url());
        $this->assertEmpty($f->getMD5());
    }

    public function testGetMD5Success(): void
    {
        global $mock_md5_file_to_string;
        $mock_md5_file_to_string = true;
        $file = $this->createVfsFile('test', $this->vfsFilePath, 'foo');
        $f = new File($file->url());
        $this->assertEquals('xx_md5file_xx', $f->getMD5());
    }
}
