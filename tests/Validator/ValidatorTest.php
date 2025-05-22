<?php

declare(strict_types=1);

namespace Platine\Test\Upload\Validator;

use InvalidArgumentException;
use Platine\Dev\PlatineTestCase;
use Platine\Upload\Validator\Rule\Required;
use Platine\Upload\Validator\Rule\UploadError;
use Platine\Upload\Validator\RuleInterface;
use Platine\Upload\Validator\Validator;
use stdClass;

/**
 * Validator class tests
 *
 * @group core
 * @group upload
 * @group validator
 */
class ValidatorTest extends PlatineTestCase
{
    public function testConstructorSuccess(): void
    {
        $f = new Validator();
        $this->assertEmpty($f->getRules());
        $f = new Validator([new Required()]);
        $this->assertCount(1, $f->getRules());
        $f->reset();
        $this->assertCount(0, $f->getRules());
    }

    public function testAddRuleSuccess(): void
    {
        $f = new Validator();
        $this->assertEmpty($f->getRules());
        $f->addRule(new Required());
        $rules = $f->getRules();
        $this->assertCount(1, $rules);
        $this->assertIsArray($rules);
        $this->assertInstanceOf(RuleInterface::class, $rules[0]);
    }

    public function testAddRulesSuccess(): void
    {
        $f = new Validator();
        $this->assertEmpty($f->getRules());
        $f->addRules([new Required(), new UploadError()]);
        $rules = $f->getRules();
        $this->assertCount(2, $rules);
        $this->assertIsArray($rules);
        $this->assertInstanceOf(RuleInterface::class, $rules[0]);
        $this->assertInstanceOf(RuleInterface::class, $rules[1]);
    }
}
