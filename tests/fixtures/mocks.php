<?php

declare(strict_types=1);

namespace Platine\Upload;

$mock_iniget_to_false = false;
$mock_iniget_to_true = false;

function ini_get(string $option)
{
    global $mock_iniget_to_false,
            $mock_iniget_to_true;

    if ($mock_iniget_to_false) {
        return false;
    }

    if ($mock_iniget_to_true) {
        return true;
    }

    return \ini_get($option);
}

namespace Platine\Http;

$mock_rename_to_true = false;
function rename(string $oldname, string $newname)
{
    global $mock_rename_to_true;

    if ($mock_rename_to_true) {
        return true;
    }

    return \rename($oldname, $newname);
}

namespace Platine\Upload\File;

$mock_md5_file_to_false = false;
$mock_md5_file_to_string = false;

function md5_file(string $filename, bool $binary = false)
{
    global $mock_md5_file_to_false,
            $mock_md5_file_to_string;

    if ($mock_md5_file_to_false) {
        return false;
    }

    if ($mock_md5_file_to_string) {
        return 'xx_md5file_xx';
    }

    return \md5_file($filename, $binary);
}

namespace Platine\Upload\Storage;

$mock_realpath = false;
$mock_file_exists_false = false;
$mock_file_exists_true = false;
$mock_copy_false = false;
$mock_copy_true = false;

function realpath(string $filename)
{
    global $mock_realpath;

    if ($mock_realpath) {
        return $filename;
    }

    return \realpath($filename);
}

function file_exists(string $filename)
{
    global $mock_file_exists_false,
            $mock_file_exists_true;

    if ($mock_file_exists_false) {
        return false;
    }

    if ($mock_file_exists_true) {
        return true;
    }

    return \file_exists($filename);
}

function copy(string $src, string $dst)
{
    global $mock_copy_false,
            $mock_copy_true;

    if ($mock_copy_false) {
        return false;
    }

    if ($mock_copy_true) {
        return true;
    }

    return \copy($src, $dst);
}
