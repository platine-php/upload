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
 *  @file Upload.php
 *
 *  The main Upload class
 *
 *  @package    Platine\Upload
 *  @author Platine Developers Team
 *  @copyright  Copyright (c) 2020
 *  @license    http://opensource.org/licenses/MIT  MIT License
 *  @link   https://www.platine-php.com
 *  @version 1.0.0
 *  @filesource
 */

declare(strict_types=1);

namespace Platine\Upload;

use Platine\Http\UploadedFile;
use Platine\Upload\File\File;
use Platine\Upload\File\UploadFileInfo;
use Platine\Upload\Storage\StorageInterface;
use Platine\Upload\Util\Helper;
use Platine\Upload\Validator\Rule\UploadError;
use Platine\Upload\Validator\RuleInterface;
use Platine\Upload\Validator\Validator;
use RuntimeException;

/**
 * @class Upload
 * @package Platine\Upload
 */
class Upload
{
    /**
     * Upload Storage
     * @var StorageInterface
     */
    protected StorageInterface $storage;

    /**
     * The upload validation object
     * @var Validator
     */
    protected Validator $validator;

    /**
     * The list of uploaded file
     * @var File[]
     */
    protected array $files = [];

    /**
     * The validations errors messages
     * @var array<int, string>
     */
    protected array $errors = [];

    /**
     * Set custom file name
     * @var string
     */
    protected string $filename;

    /**
     * The uploaded file information
     * @var UploadFileInfo|UploadFileInfo[]|bool
     */
    protected UploadFileInfo|array|bool $uploadInfo = false;

    /**
     * Create new instance
     * @param string $key the file key to use
     * @param StorageInterface $storage
     * @param Validator|null $validator
     * @param array<mixed> $uploadedFiles
     */
    public function __construct(
        string $key,
        StorageInterface $storage,
        ?Validator $validator = null,
        array $uploadedFiles = []
    ) {
        if ((bool) ini_get('file_uploads') === false) {
            throw new RuntimeException('File uploads are disabled in your PHP.ini file');
        }

        $this->storage = $storage;
        $this->validator = $validator ?? new Validator();

        if (count($uploadedFiles) === 0) {
            $uploadedFiles = UploadedFile::createFromGlobals();
        }

        $files = Helper::normalizeFiles($uploadedFiles);

        if (array_key_exists($key, $files)) {
            $fileInfo = $files[$key];
            if (is_array($fileInfo)) {
                foreach ($fileInfo as $file) {
                    $this->files[] = $file;
                }
            } else {
                $this->files[] = $fileInfo;
            }
        }

        //add default validation rule
        $this->addDefaultValidations();
    }

    /**
    * Whether the file is uploaded
    */
    public function isUploaded(): bool
    {
        return count($this->files) > 0;
    }

    /**
     * Set custom filename
     * @param string $name
     * @return $this
     */
    public function setFilename(string $name): self
    {
        foreach ($this->files as $file) {
            $file->setName($name);
        }

        return $this;
    }

    /**
     * Shortcut to Validator::addRule
     * @param RuleInterface $rule
     * @return $this
     */
    public function addValidation(RuleInterface $rule): self
    {
        $this->validator->addRule($rule);

        return $this;
    }

    /**
     * Add validations array
     * @param RuleInterface[] $rules
     * @return $this
     */
    public function addValidations(array $rules): self
    {
        $this->validator->addRules($rules);

        return $this;
    }

    /**
     * Check whether the file to upload is valid
     * @return bool
     */
    public function isValid(): bool
    {
        foreach ($this->files as $file) {
            $this->validateFile($file);
        }

        return count($this->errors) === 0;
    }

    /**
     * Process upload
     * @return bool
     */
    public function process(): bool
    {
        if ($this->isValid() === false) {
            return false;
        }

        $result = [];

        foreach ($this->files as $file) {
            if ($file->getError() === UPLOAD_ERR_OK) {
                $result[] = $this->storage->upload($file);
            }
        }

        if (count($result) === 1) {
            $this->uploadInfo = $result[0];
        } else {
            $this->uploadInfo = $result;
        }

        return true;
    }

    /**
     * Return the validation errors
     * @return array<int, string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Return the uploaded file information
     * @return UploadFileInfo|UploadFileInfo[]|bool
     */
    public function getInfo(): UploadFileInfo|array|bool
    {
        return $this->uploadInfo;
    }

    /**
     * Validate the uploaded file
     * @param File $file
     * return void
     */
    protected function validateFile(File $file): void
    {
        foreach ($this->validator->getRules() as $rule) {
            if ($rule->validate($file) === false) {
                $this->errors[] = $rule->getErrorMessage($file);
                break;
            }
        }
    }

    /**
     * Add default rules validations
     * @return void
     */
    protected function addDefaultValidations(): void
    {
        $this->validator->addRules([
            new UploadError()
        ]);
    }
}
