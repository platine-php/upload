<?php

declare(strict_types=1);

namespace Platine\Test\Upload\Validator\Rule;

use Platine\Dev\PlatineTestCase;
use Platine\Upload\File\File;
use Platine\Upload\Validator\Rule\Size;

/**
 * Size class tests
 *
 * @group core
 * @group upload
 * @group rule
 */
class SizeTest extends PlatineTestCase
{

    public function testParamIsInteger(): void
    {
        $f = new Size(1024);
        $file = $this->getMockInstance(File::class);
        $this->assertTrue($f->validate($file));
    }

    public function testParamIsString(): void
    {
        $f = new Size('1M');
        $file = $this->getMockInstance(File::class);
        $this->assertTrue($f->validate($file));
        $this->assertEquals($this->getPropertyValue(Size::class, $f, 'size'), 1048576);
    }

    public function testAllFailed(): void
    {
        $f = new Size('1K');
        $file = $this->getMockInstance(File::class, ['getSize' => 1000000]);
        $this->assertFalse($f->validate($file));
        $this->assertNotEmpty($f->getErrorMessage($file));
    }
}
