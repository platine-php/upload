<?php

declare(strict_types=1);

namespace Platine\Test\Upload\Validator\Rule;

use Platine\Dev\PlatineTestCase;
use Platine\Upload\File\File;
use Platine\Upload\Validator\Rule\Required;

/**
 * Required class tests
 *
 * @group core
 * @group upload
 * @group rule
 */
class RequiredTest extends PlatineTestCase
{

    public function testAllSuccess(): void
    {
        $f = new Required();
        $file = $this->getMockInstance(File::class);
        $this->assertTrue($f->validate($file));
        $this->assertNotEmpty($f->getErrorMessage($file));
    }

    public function testAllFailed(): void
    {
        $f = new Required();
        $file = $this->getMockInstance(File::class, ['getError' => UPLOAD_ERR_NO_FILE]);
        $this->assertFalse($f->validate($file));
    }
}
