<?php

declare(strict_types=1);

namespace Platine\Test\Upload\Util;

use Platine\Dev\PlatineTestCase;
use Platine\Upload\Util\Helper;

/**
 * Helper class tests
 *
 * @group core
 * @group upload
 * @group rule
 */
class HelperTest extends PlatineTestCase
{

    public function testSizeInBytes(): void
    {
        $expectedB = 1;
        $expectedK = 1024 ;
        $expectedM = 1048576;
        $expectedG = 1073741824;
        $expectedT = 1099511627776;
        $resultB = Helper::sizeInBytes('1B');
        $resultb = Helper::sizeInBytes('1b');
        $resultk = Helper::sizeInBytes('1k');
        $resultK = Helper::sizeInBytes('1K');
        $resultM = Helper::sizeInBytes('1M');
        $resultm = Helper::sizeInBytes('1m');
        $resultg = Helper::sizeInBytes('1g');
        $resultG = Helper::sizeInBytes('1G');
        $resultT = Helper::sizeInBytes('1T');
        $resultt = Helper::sizeInBytes('1t');
        $this->assertEquals($expectedB, $resultB);
        $this->assertEquals($expectedB, $resultb);
        $this->assertEquals($expectedK, $resultK);
        $this->assertEquals($expectedK, $resultk);
        $this->assertEquals($expectedM, $resultM);
        $this->assertEquals($expectedM, $resultm);
        $this->assertEquals($expectedG, $resultG);
        $this->assertEquals($expectedG, $resultg);
        $this->assertEquals($expectedT, $resultT);
        $this->assertEquals($expectedT, $resultt);
    }

    public function testFormatSizeLessOrEqualZero(): void
    {
        $this->assertEmpty(Helper::formatSize(-10));
        $this->assertEmpty(Helper::formatSize(0));
    }

    public function testFormatSizeMoreThanZero(): void
    {
        $this->assertEquals('1B', Helper::formatSize(1));
        $this->assertEquals('1K', Helper::formatSize(1024));
        $this->assertEquals('1M', Helper::formatSize(1048576));
        $this->assertEquals('1G', Helper::formatSize(1073741824));
        $this->assertEquals('1T', Helper::formatSize(1099511627776));
        $this->assertEquals('1.01K', Helper::formatSize(1030));
        $this->assertEquals('1019.61M', Helper::formatSize(1069141824));
        $this->assertEquals('16.95G', Helper::formatSize(18199991824));
    }
}
