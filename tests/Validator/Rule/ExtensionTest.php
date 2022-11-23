<?php

declare(strict_types=1);

namespace Platine\Test\Upload\Validator\Rule;

use Platine\Dev\PlatineTestCase;
use Platine\Upload\File\File;
use Platine\Upload\Validator\Rule\Extension;
use Platine\Upload\Validator\Rule\MimeType;

/**
 * Extension class tests
 *
 * @group core
 * @group upload
 * @group rule
 */
class ExtensionTest extends PlatineTestCase
{
    public function testParamIsString(): void
    {
        $f = new Extension('png');
        $file = $this->getMockInstance(File::class, ['getExtension' => 'png']);
        $this->assertTrue($f->validate($file));
    }

    public function testParamIsArray(): void
    {
        $f = new Extension(['png', 'jpg']);
        $file = $this->getMockInstance(File::class, ['getExtension' => 'png']);
        $this->assertTrue($f->validate($file));
        $file2 = $this->getMockInstance(File::class, ['getExtension' => 'jpg']);
        $this->assertTrue($f->validate($file2));
        $this->assertCount(2, $this->getPropertyValue(Extension::class, $f, 'extensions'));
    }

    public function testStringFailed(): void
    {
        $f = new Extension('txt');
        $file = $this->getMockInstance(File::class, ['getExtension' => 'png']);
        $this->assertFalse($f->validate($file));
        $this->assertNotEmpty($f->getErrorMessage($file));
    }

    public function testArrayFailed(): void
    {
        $f = new Extension(['png', 'jpg']);
        $file = $this->getMockInstance(File::class, ['getExtension' => 'txt']);
        $this->assertFalse($f->validate($file));
        $file2 = $this->getMockInstance(File::class, ['getExtension' => 'php']);
        $this->assertFalse($f->validate($file2));
    }

    public function testAllExclude(): void
    {
        $f = new Extension(['png', 'jpg'], true);
        $file = $this->getMockInstance(File::class, ['getExtension' => 'png']);
        $this->assertFalse($f->validate($file));
        $file2 = $this->getMockInstance(File::class, ['getExtension' => 'txt']);
        $this->assertTrue($f->validate($file2));
    }
}
