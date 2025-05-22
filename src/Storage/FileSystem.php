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
 *  @file FileSystem.php
 *
 *  The Upload File system storage class
 *
 *  @package    Platine\Upload\Storage
 *  @author Platine Developers Team
 *  @copyright  Copyright (c) 2020
 *  @license    http://opensource.org/licenses/MIT  MIT License
 *  @link   https://www.platine-php.com
 *  @version 1.0.0
 *  @filesource
 */

declare(strict_types=1);

namespace Platine\Upload\Storage;

use InvalidArgumentException;
use Platine\Upload\Exception\StorageException;
use Platine\Upload\Exception\UploadException;
use Platine\Upload\File\File;
use Platine\Upload\File\UploadFileInfo;

/**
 * @class FileSystem
 * @package Platine\Upload\Storage
 */
class FileSystem implements StorageInterface
{
    /**
     * Path to move uploaded files
     * @var string
     */
    protected string $path;

    /**
     * Whether to overwrite existing file
     * @var bool
     */
    protected bool $overwrite = false;

    /**
     * Create new instance
     * @param string $path
     * @param bool $overwrite
     */
    public function __construct(string $path, bool $overwrite)
    {
        $this->overwrite = $overwrite;
        $directory = $this->normalizePath($path);

        if (is_dir($directory) === false || is_writable($directory) === false) {
            throw new InvalidArgumentException(sprintf(
                'Directory [%s] does not exist or is not writable',
                $directory
            ));
        }

        $this->path = $directory;
    }

    /**
     * {@inheritdoc}
     */
    public function upload(File $file): UploadFileInfo
    {
        $destinationFile = $this->path . $file->getFullName();
        if ($this->overwrite === false && file_exists($destinationFile)) {
            throw new StorageException(sprintf(
                'File [%s] already exists',
                $destinationFile
            ));
        }

        $uploaded = $this->moveUploadedFile($file->getPathname(), $destinationFile);
        if ($uploaded) {
            return new UploadFileInfo(
                $destinationFile,
                $file->getMimeType(),
                $file->getError(),
                $file->getSize()
            );
        }

        throw new UploadException(sprintf(
            'Error occured when move uploaded file [%s] to [%s]',
            $file->getPathname(),
            $destinationFile
        ));
    }

    /**
     * Move the uploaded file to final destination
     * @param string $source
     * @param string $destination
     * @return bool
     */
    protected function moveUploadedFile(string $source, string $destination): bool
    {
        return copy($source, $destination);
    }

    /**
     * Normalize the directory path
     * @param string $path
     * @return string
     */
    protected function normalizePath(string $path): string
    {
        $directory = rtrim($path, '\\/');

        return realpath($directory) . DIRECTORY_SEPARATOR;
    }
}
