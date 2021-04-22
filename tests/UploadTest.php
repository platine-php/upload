<?php

declare(strict_types=1);

namespace Platine\Test\Upload;

use org\bovigo\vfs\vfsStream;
use Platine\PlatineTestCase;
use Platine\Upload\File\File;
use Platine\Upload\File\UploadFileInfo;
use Platine\Upload\Storage\FileSystem;
use Platine\Upload\Upload;
use Platine\Upload\Validator\Rule\Required;
use Platine\Upload\Validator\Rule\Size;
use Platine\Upload\Validator\Validator;
use RuntimeException;

/**
 * Upload class tests
 *
 * @group core
 * @group upload
 */
class UploadTest extends PlatineTestCase
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


    public function testConstructorUploadIsDisabled()
    {
        global $mock_iniget_to_false;
        $mock_iniget_to_false = true;

        $storage = $this->getMockInstance(FileSystem::class);
        $this->expectException(RuntimeException::class);
        $u = new Upload('foo', $storage);
    }

    public function testConstructorSuccessUploadedFileSingle()
    {
        global $mock_iniget_to_true, $mock_rename_to_true;

        $mock_iniget_to_true = true;
        $mock_rename_to_true = true;

        $tmpFile = $this->createVfsFile('foo.tmp.09080', $this->vfsFilePath, 'foobar');

        $_FILES['foo'] = [
            'name' => 'foo.png',
            'tmp_name' => $tmpFile->url(),
            'size' => 134,
            'error' => 0,
            'type' => 'image/png'
        ];

        $storage = $this->getMockInstance(FileSystem::class);
        $u = new Upload('foo', $storage);
        $this->assertTrue($u->process());
    }

    public function testConstructorSuccessUploadedFileMultiple()
    {
        global $mock_iniget_to_true, $mock_rename_to_true;

        $mock_iniget_to_true = true;
        $mock_rename_to_true = true;

        $tmpFile = $this->createVfsFile('foo.tmp.09080', $this->vfsFilePath, 'foobar');

        $_FILES['foo'] = [[
            'name' => 'foo.png',
            'tmp_name' => $tmpFile->url(),
            'size' => 134,
            'error' => 0,
            'type' => 'image/png'
        ]];

        $storage = $this->getMockInstance(FileSystem::class);
        $u = new Upload('foo', $storage);
        $this->assertTrue($u->process());
    }

    public function testSetFilename()
    {
        global $mock_iniget_to_true, $mock_rename_to_true;

        $mock_iniget_to_true = true;
        $mock_rename_to_true = true;

        $tmpFile = $this->createVfsFile('foo.tmp.09080', $this->vfsFilePath, 'foobar');

        $_FILES['foo'] = [
            'name' => 'foo.png',
            'tmp_name' => $tmpFile->url(),
            'size' => 134,
            'error' => 0,
            'type' => 'image/png'
        ];

        $storage = $this->getMockInstance(FileSystem::class);
        $u = new Upload('foo', $storage);

        $file = $this->getPropertyValue(Upload::class, $u, 'files');

        $this->assertIsArray($file);
        $this->assertInstanceOf(File::class, $file[0]);
        $this->assertEquals('foo.png', $file[0]->getFullName());

        $u->setFilename('myfile');
        $this->assertEquals('myfile.png', $file[0]->getFullName());
    }

    public function testAddValidation()
    {
        global $mock_iniget_to_true, $mock_rename_to_true;

        $mock_iniget_to_true = true;
        $mock_rename_to_true = true;

        $tmpFile = $this->createVfsFile('foo.tmp.09080', $this->vfsFilePath, 'foobar');

        $_FILES['foo'] = [
            'name' => 'foo.png',
            'tmp_name' => $tmpFile->url(),
            'size' => 134,
            'error' => 0,
            'type' => 'image/png'
        ];

        $storage = $this->getMockInstance(FileSystem::class);
        $u = new Upload('foo', $storage);

        $required = $this->getMockInstance(Required::class);


        /** @var Validator $validator */
        $validator = $this->getPropertyValue(Upload::class, $u, 'validator');
        //Already have default validators added
        $this->assertCount(1, $validator->getRules());

        $u->addValidation($required);

        $this->assertCount(2, $validator->getRules());
    }

    public function testAddValidationArray()
    {
        global $mock_iniget_to_true, $mock_rename_to_true;

        $mock_iniget_to_true = true;
        $mock_rename_to_true = true;

        $tmpFile = $this->createVfsFile('foo.tmp.09080', $this->vfsFilePath, 'foobar');

        $_FILES['foo'] = [
            'name' => 'foo.png',
            'tmp_name' => $tmpFile->url(),
            'size' => 134,
            'error' => 0,
            'type' => 'image/png'
        ];

        $storage = $this->getMockInstance(FileSystem::class);
        $u = new Upload('foo', $storage);

        $required = $this->getMockInstance(Required::class);


        /** @var Validator $validator */
        $validator = $this->getPropertyValue(Upload::class, $u, 'validator');
        //Already have default validators added
        $this->assertCount(1, $validator->getRules());

        $this->assertCount(1, $validator->getRules());
        $u->addValidations([$required]);
        $this->assertCount(2, $validator->getRules());
    }

    public function testValidateFailed()
    {
        global $mock_iniget_to_true, $mock_rename_to_true;

        $mock_iniget_to_true = true;
        $mock_rename_to_true = true;

        $tmpFile = $this->createVfsFile('foo.tmp.09080', $this->vfsFilePath, 'foobar');

        $_FILES['foo'] = [
            'name' => 'foo.png',
            'tmp_name' => $tmpFile->url(),
            'size' => 134,
            'error' => 0,
            'type' => 'image/png'
        ];

        $storage = $this->getMockInstance(FileSystem::class);
        $u = new Upload('foo', $storage);

        $required = $this->getMockInstance(Required::class, ['validate' => false]);

        $u->addValidation($required);
        $this->assertFalse($u->isValid());
    }

    public function testValidateSuccess()
    {
        global $mock_iniget_to_true, $mock_rename_to_true;

        $mock_iniget_to_true = true;
        $mock_rename_to_true = true;

        $tmpFile = $this->createVfsFile('foo.tmp.09080', $this->vfsFilePath, 'foobar');

        $_FILES['foo'] = [
            'name' => 'foo.png',
            'tmp_name' => $tmpFile->url(),
            'size' => 134,
            'error' => 0,
            'type' => 'image/png'
        ];

        $storage = $this->getMockInstance(FileSystem::class);
        $u = new Upload('foo', $storage);

        $required = $this->getMockInstance(Required::class, ['validate' => true]);
        $size = $this->getMockInstance(Size::class, ['validate' => true]);

        $u->addValidations([$required, $size]);
        $this->assertTrue($u->isValid());
    }

    public function testProcessFailed()
    {
        global $mock_iniget_to_true, $mock_rename_to_true;

        $mock_iniget_to_true = true;
        $mock_rename_to_true = true;

        $tmpFile = $this->createVfsFile('foo.tmp.09080', $this->vfsFilePath, 'foobar');

        $_FILES['foo'] = [
            'name' => 'foo.png',
            'tmp_name' => $tmpFile->url(),
            'size' => 134,
            'error' => 0,
            'type' => 'image/png'
        ];

        $storage = $this->getMockInstance(FileSystem::class);
        $u = new Upload('foo', $storage);

        $required = $this->getMockInstance(Required::class, ['validate' => false]);

        $u->addValidation($required);
        $this->assertFalse($u->process());
        $this->assertCount(1, $u->getErrors());
    }

    public function testProcessSuccessSingle()
    {
        global $mock_iniget_to_true, $mock_rename_to_true;

        $mock_iniget_to_true = true;
        $mock_rename_to_true = true;

        $tmpFile = $this->createVfsFile('foo.tmp.09080', $this->vfsFilePath, 'foobar');

        $_FILES['foo'] = [
            'name' => 'foo.png',
            'tmp_name' => $tmpFile->url(),
            'size' => 134,
            'error' => 0,
            'type' => 'image/png'
        ];

        $storage = $this->getMockInstance(FileSystem::class);
        $u = new Upload('foo', $storage);

        $required = $this->getMockInstance(Required::class, ['validate' => true]);

        $u->addValidation($required);
        $this->assertTrue($u->process());

        $info = $u->getInfo();
        $this->assertInstanceOf(UploadFileInfo::class, $info);
    }

    public function testProcessSuccessMultiple()
    {
        global $mock_iniget_to_true, $mock_rename_to_true;

        $mock_iniget_to_true = true;
        $mock_rename_to_true = true;

        $tmpFile = $this->createVfsFile('foo.tmp.09080', $this->vfsFilePath, 'foobar');

        $_FILES['foo'] = [
            [
                'name' => 'foo.png',
                'tmp_name' => $tmpFile->url(),
                'size' => 134,
                'error' => 0,
                'type' => 'image/png'
            ],
            [
                'name' => 'foobar.png',
                'tmp_name' => $tmpFile->url(),
                'size' => 100,
                'error' => 0,
                'type' => 'image/jpg'
            ]
        ];

        $storage = $this->getMockInstance(FileSystem::class);
        $u = new Upload('foo', $storage);

        $required = $this->getMockInstance(Required::class, ['validate' => true]);

        $u->addValidation($required);
        $this->assertTrue($u->process());

        $infos = $u->getInfo();
        $this->assertIsArray($infos);
        $this->assertCount(2, $infos);
        $this->assertInstanceOf(UploadFileInfo::class, $infos[0]);
        $this->assertInstanceOf(UploadFileInfo::class, $infos[1]);
    }
}
