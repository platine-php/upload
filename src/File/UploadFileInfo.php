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
 *  @file UploadFileInfo.php
 *
 *  The Upload File information class
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
 * @class UploadFileInfo
 * @package Platine\Upload\File
 */
class UploadFileInfo
{
    /**
     * The full file name
     * @var string
     */
    protected string $fullName;

    /**
     * The file name (without extension)
     * @var string
     */
    protected string $name;

    /**
     * The file extension
     * @var string
     */
    protected string $extension;

    /**
     * The file mime type
     * @var string
     */
    protected string $mimeType = '';

    /**
     * The upload status
     * @var int
     */
    protected int $error;

    /**
     * The upload file size
     * @var int
     */
    protected int $size;

    /**
     * The file full path
     * @var string
     */
    protected string $path;

    /**
     * The file checksum
     * @var string
     */
    protected string $checksum;

    /**
     * Create new instance
     * @param string $path
     * @param string $mimeType
     * @param int $error
     * @param int $size
     * @param string $checksum
     */
    public function __construct(
        string $path,
        string $mimeType,
        int $error,
        int $size,
        string $checksum
    ) {
        $this->name = pathinfo($path, PATHINFO_FILENAME);
        $this->extension = pathinfo($path, PATHINFO_EXTENSION);
        $this->mimeType = $mimeType;
        $this->error = $error;
        $this->size = $size;
        $this->path = $path;
        $this->checksum = $checksum;
        $this->fullName = basename($path);
    }

    /**
     * Get the full name of the file
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * Get the checksum of the file
     * @return string
     */
    public function getChecksum(): string
    {
        return $this->checksum;
    }

    /**
     * Return the name of the file without extension
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Return the extension of the file
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * Return the mime type of the file
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * Return the file error code
     * @return int
     */
    public function getError(): int
    {
        return $this->error;
    }

    /**
     * Return the file size in byte
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Return the file full path
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
