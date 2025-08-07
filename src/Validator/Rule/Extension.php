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
 *  @file Extension.php
 *
 *  The file upload extension validation rule class
 *
 *  @package    Platine\Upload\Validator
 *  @author Platine Developers Team
 *  @copyright  Copyright (c) 2020
 *  @license    http://opensource.org/licenses/MIT  MIT License
 *  @link   https://www.platine-php.com
 *  @version 1.0.0
 *  @filesource
 */

declare(strict_types=1);

namespace Platine\Upload\Validator\Rule;

use Platine\Stdlib\Helper\Arr;
use Platine\Upload\File\File;
use Platine\Upload\Validator\RuleInterface;

/**
 * @class Extension
 * @package Platine\Upload\Validator\Rule
 */
class Extension implements RuleInterface
{
    /**
     * The list of allowed/forbidden extensions
     * @var array<int, string>
     */
    protected array $extensions;

    /**
     * Whether the extension list is to allow/forbidden
     * @var bool
s     */
    protected bool $exclude = false;

    /**
     * Create new instance
     * @param array<int, string>|string $extensions
     * @param bool $exclude
     */
    public function __construct(array|string $extensions, bool $exclude = false)
    {
        $this->extensions = Arr::wrap($extensions);
        $this->exclude = $exclude;
    }

    /**
     * {@inheritdoc}
     * @see RuleInterface
     */
    public function validate(File $file): bool
    {
        $extension = strtolower($file->getExtension());
        $extensions = array_map('strtolower', $this->extensions);
        $result = in_array($extension, $extensions);

        if ($result && $this->exclude) {
            return false;
        }

        if ($result === false && $this->exclude === false) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     * @see RuleInterface
     */
    public function getErrorMessage(File $file): string
    {
        return sprintf(
            'The uploaded file extension [%s] is not allowed or forbidden',
            $file->getExtension()
        );
    }
}
