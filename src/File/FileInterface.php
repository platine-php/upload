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
 *  @file FileInterface.php
 *
 *  The Uploaded file FileInterface
 *
 *  @package    Platine\Upload\File
 *  @author Platine Developers Team
 *  @copyright  Copyright (c) 2020
 *  @license    http://opensource.org/licenses/MIT  MIT License
 *  @link   https://www.platine-php.com
 *  @version 1.0.0
 *  @filesource
 */

declare(strict_types=1);

namespace Platine\Upload\File;

/**
 * Class FileInterface
 * @package Platine\Upload\File
 */
interface FileInterface
{
    /**
     * Return the path name of uploaded file
     * @return string
     */
    public function getPathname(): string;

    /**
     * Return the name of uploaded file
     * @return string
     */
    public function getName(): string;

    /**
     * Set the uploaded file name
     * @param string $name
     * @return self
     */
    public function setName(string $name): self;

    /**
     * Return the name of uploaded file extension
     * @return string
     */
    public function getExtension(): string;

    /**
     * Set the uploaded file extension
     * @param string $name
     * @return self
     */
    public function setExtension(string $name): self;

    /**
     * Return the full name (name with extension) of uploaded file
     * @return string
     */
    public function getFullName(): string;

    /**
     * Return the mime type of uploaded file
     * @return string
     */
    public function getMimeType(): string;

    /**
     * Return the size of uploaded file
     * @return int
     */
    public function getSize(): int;

    /**
     * Return the MD5 hash of uploaded file
     * @return string
     */
    public function getMD5(): string;

    /**
     * Return the error code of uploaded file
     * @return int
     */
    public function getError(): int;
}
