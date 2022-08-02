<?php

/**
 * Platine Upload
 *
 * Platine Upload provides a flexible file uploads with extensible
 * validation and storage strategies.
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2020 Platine Upload
 *
 * @author      Josh Lockhart <info@joshlockhart.com>
 * @copyright   2012 Josh Lockhart
 * @link        http://www.joshlockhart.com
 * @version     2.0.0
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 *  @file Validator.php
 *
 *  The Upload Validator class
 *
 *  @package    Platine\Upload\Validator
 *  @author Platine Developers Team
 *  @copyright  Copyright (c) 2020
 *  @license    http://opensource.org/licenses/MIT  MIT License
 *  @link   http://www.iacademy.cf
 *  @version 1.0.0
 *  @filesource
 */

declare(strict_types=1);

namespace Platine\Upload\Validator;

use InvalidArgumentException;

/**
 * Class Validator
 * @package Platine\Upload\Validator
 */
class Validator
{
    /**
     * The validate rules
     * @var array<int, RuleInterface>
     */
    protected array $rules = [];

    /**
     * Create new instance
     * @param array<int, RuleInterface> $rules
     */
    public function __construct(array $rules = [])
    {
        $this->rules = $rules;
    }

    /**
     * Add validation rule
     * @param RuleInterface $rule
     * @return $this
     */
    public function addRule(RuleInterface $rule): self
    {
        $this->rules[] = $rule;

        return $this;
    }

    /**
     * Add array of rules
     * @param array<int, RuleInterface> $rules
     * @return $this
     */
    public function addRules(array $rules): self
    {
        foreach ($rules as $rule) {
            if (!$rule instanceof RuleInterface) {
                throw new InvalidArgumentException(sprintf(
                    'Each rule must be an instance of [%s]',
                    RuleInterface::class
                ));
            }

            $this->addRule($rule);
        }

        return $this;
    }

    /**
     * Return the validation rules
     * @return array<int, RuleInterface>
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * Reset the validation instance
     * @return $this
     */
    public function reset(): self
    {
        $this->rules = [];

        return $this;
    }
}
