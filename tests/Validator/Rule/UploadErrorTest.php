<?php

declare(strict_types=1);

namespace Platine\Test\Upload\Validator\Rule;

use Platine\Dev\PlatineTestCase;
use Platine\Upload\File\File;
use Platine\Upload\Validator\Rule\UploadError;

/**
 * UploadError class tests
 *
 * @group core
 * @group upload
 * @group rule
 */
class UploadErrorTest extends PlatineTestCase
{

    public function testAllSuccess(): void
    {
        $f = new UploadError();
        $file = $this->getMockInstance(File::class);
        $this->assertTrue($f->validate($file));
    }

    public function testAllFailed(): void
    {
        $f = new UploadError();
        $file = $this->getMockInstance(File::class, ['getError' => 1]);
        $this->assertFalse($f->validate($file));
        $this->assertNotEmpty($f->getErrorMessage($file));
    }
}
