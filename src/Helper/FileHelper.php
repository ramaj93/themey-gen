<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace themey\Helper;

/**
 * Description of FileHelper
 *
 * @author Ramadan Juma
 */
class FileHelper {

    public static function createFolders($dirs = [], $context = FALSE, $call_back = false) {
        foreach ($dirs as $dir => $child) {
            if (!file_exists($context)) {
                mkdir($context, 0777, TRUE);
            }
            if ($context != FALSE) {
                chdir($context);
            }
            if (!is_numeric($dir)) {
                if (!file_exists($dir))
                    mkdir($dir, 0777, true);
            } else {
                if (!file_exists($child))
                    mkdir($child);
            }
            if (is_array($child)) {
                self::createFolders($child, realpath($dir));
            }
        }
        return TRUE;
    }

    public static function copyFile($src, $dest) {
        if (!is_file($dest)) {
            if (!file_exists($dest)) {
                mkdir($dest, 0777, TRUE);
            }
            $dest = $dest . DIRECTORY_SEPARATOR . basename($src);
        }
        return copy($src, $dest);
    }

    public static function copyDirectory($src, $dst, $options = []) {

        if ($src === $dst || strpos($dst, $src . DIRECTORY_SEPARATOR) === 0) {
            throw new Exception('Trying to copy a directory to itself or a subdirectory.');
        }
        if (!is_dir($dst)) {
            mkdir($dst, 0777, TRUE);
        }

        $handle = opendir($src);
        if ($handle === false) {
            throw new Exception("Unable to open directory: $src");
        }
        if (!isset($options['basePath'])) {
            // this should be done only once
            $options['basePath'] = realpath($src);
        }
        while (($file = readdir($handle)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $from = $src . DIRECTORY_SEPARATOR . $file;
            $to = $dst . DIRECTORY_SEPARATOR . $file;
            if (TRUE) {
                if (isset($options['beforeCopy']) && !call_user_func($options['beforeCopy'], $from, $to)) {
                    continue;
                }
                if (is_file($from)) {
                    copy($from, $to);
                    if (isset($options['fileMode'])) {
                        @chmod($to, $options['fileMode']);
                    }
                } else {
                    // recursive copy, defaults to true
                    if (!isset($options['recursive']) || $options['recursive']) {
                        static::copyDirectory($from, $to, $options);
                    }
                }
                if (isset($options['afterCopy'])) {
                    call_user_func($options['afterCopy'], $from, $to);
                }
            }
        }
        closedir($handle);
    }

    static function renderFile($_viewFile_, $_data_ = false,$_return_ = true) {
        if (is_array($_data_))
            extract($_data_, EXTR_PREFIX_SAME, 'data');
        else
            $data = $_data_;
        if ($_return_) {
            ob_start();
            ob_implicit_flush(false);
            require($_viewFile_);
            return ob_get_clean();
        } else
            require($_viewFile_);
    }

}
