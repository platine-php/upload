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
 *  @file File.php
 *
 *  The Upload File class
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

use finfo;
use RuntimeException;
use SplFileInfo;

/**
 * @class File
 * @package Platine\Upload\File
 */
class File extends SplFileInfo implements FileInterface
{
    /**
     * Factory used to create new instance
     * @var callable|null
     */
    protected static $factory = null;

    /**
     * The file name
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
    protected int $error = UPLOAD_ERR_OK;


    /**
     * Create new instance
     * @param string $filePath the file absolute path
     * @param string|null $name the desired new name
     * @param int $error
     */
    public function __construct(
        string $filePath,
        ?string $name = null,
        int $error = UPLOAD_ERR_OK
    ) {
        $this->error = $error;
        $newName = $name === null ? $filePath : $name;

        $this->setName(pathinfo($newName, PATHINFO_FILENAME));
        $this->setExtension(pathinfo($newName, PATHINFO_EXTENSION));

        parent::__construct($filePath);
    }

    /**
    * {@inheritdoc}
    */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
    * {@inheritdoc}
    */
    public function setExtension(string $name): self
    {
        $this->extension = strtolower($name);

        return $this;
    }

    /**
    * {@inheritdoc}
    */
    public function getFullName(): string
    {
        return empty($this->extension) ?
                $this->name
                : sprintf('%s.%s', $this->name, $this->extension);
    }

    /**
    * {@inheritdoc}
    */
    public function getMD5(): string
    {
        $hash = md5_file($this->getPathname());
        return $hash === false ? '' : $hash;
    }

    /**
    * {@inheritdoc}
    */
    public function getPathname(): string
    {
        return parent::getPathname();
    }

    /**
    * {@inheritdoc}
    */
    public function getSize(): int
    {
        return parent::getSize();
    }

    /**
    * {@inheritdoc}
    */
    public function getMimeType(): string
    {
        if (empty($this->mimeType)) {
            $finfo = new finfo(FILEINFO_MIME);
            $mimeType = $finfo->file($this->getPathname());
            if ($mimeType !== false) {
                $mimetypeParts = preg_split('/\s*[;,]\s*/', $mimeType);
                if (is_array($mimetypeParts)) {
                    $this->mimeType = strtolower($mimetypeParts[0]);
                }
            }
            unset($finfo);
        }

        return $this->mimeType;
    }

    /**
    * {@inheritdoc}
    */
    public function getName(): string
    {
        return $this->name;
    }

    /**
    * {@inheritdoc}
    */
    public function setName(string $name): self
    {
        $cleanName = preg_replace('/[^A-Za-z0-9\.]+/', '_', $name);
        if ($cleanName !== null) {
            $filename = basename($cleanName);
            $this->name = $filename;
        }

        return $this;
    }

    /**
    * {@inheritdoc}
    */
    public function getError(): int
    {
        return $this->error;
    }

    /**
     * Set the factory used to create new instance
     * @param callable|null $callable
     * @return void
     */
    public static function setFactory(?callable $callable = null): void
    {
        static::$factory = $callable;
    }

    /**
     * Create new instance of this class
     * @param string $tmpName
     * @param string|null $name
     * @param int $error
     * @return self
     */
    public static function create(
        string $tmpName,
        ?string $name = null,
        int $error = UPLOAD_ERR_OK
    ): self {
        if (static::$factory !== null) {
            $file = call_user_func_array(static::$factory, [$tmpName, $name, $error]);

            if (!$file instanceof File) {
                throw new RuntimeException(sprintf(
                    'The File factory must return an instance of [%s]',
                    File::class
                ));
            }

            return $file;
        }

        return new self($tmpName, $name, $error);
    }
}
