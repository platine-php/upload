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
 *  @file Size.php
 *
 *  The max file upload size validation rule class
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

use Platine\Upload\File\File;
use Platine\Upload\Util\Helper;
use Platine\Upload\Validator\RuleInterface;

/**
 * @class Size
 * @package Platine\Upload\Validator\Rule
 */
class Size implements RuleInterface
{
    /**
     * The uploaded file max size
     * @var int
     */
    protected int $size;

    /**
     * Create new instance
     * @param int|string $size
     */
    public function __construct(int|string $size)
    {
        if (is_int($size) === false) {
            $size = Helper::sizeInBytes($size);
        }

        $this->size = $size;
    }

    /**
     * {@inheritdoc}
     * @see RuleInterface
     */
    public function validate(File $file): bool
    {
        return $file->getSize() <= $this->size;
    }

    /**
     * {@inheritdoc}
     * @see RuleInterface
     */
    public function getErrorMessage(File $file): string
    {
        return sprintf(
            'The uploaded file size [%s] is too big, max file size is [%s]',
            Helper::formatSize($file->getSize()),
            Helper::formatSize($this->size)
        );
    }
}
