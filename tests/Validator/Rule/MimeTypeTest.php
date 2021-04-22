<?php

declare(strict_types=1);

namespace Platine\Test\Upload\Validator\Rule;

use Platine\PlatineTestCase;
use Platine\Upload\File\File;
use Platine\Upload\Validator\Rule\MimeType;

/**
 * MimeType class tests
 *
 * @group core
 * @group upload
 * @group rule
 */
class MimeTypeTest extends PlatineTestCase
{

    public function testParamIsString(): void
    {
        $f = new MimeType('image/png');
        $file = $this->getMockInstance(File::class, ['getMimeType' => 'image/png']);
        $this->assertTrue($f->validate($file));
    }

    public function testParamIsArray(): void
    {
        $f = new MimeType(['image/png', 'image/jpg']);
        $file = $this->getMockInstance(File::class, ['getMimeType' => 'image/png']);
        $this->assertTrue($f->validate($file));
        $file2 = $this->getMockInstance(File::class, ['getMimeType' => 'image/jpg']);
        $this->assertTrue($f->validate($file2));
        $this->assertCount(2, $this->getPropertyValue(MimeType::class, $f, 'mimeTypes'));
    }

    public function testAllFailed(): void
    {
        $f = new MimeType('text/plain');
        $file = $this->getMockInstance(File::class, ['getMimeType' => 'image/png']);
        $this->assertFalse($f->validate($file));
        $this->assertNotEmpty($f->getErrorMessage($file));
    }
}
