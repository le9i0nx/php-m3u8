<?php
/**
 * Project: php-m3u8-sdk
 * File: File.php 2015-08-18 13:40
 * ----------------------------------------------
 *
 * @author      Stanislav Kiryukhin <korsar.zn@gmail.com>
 * @copyright   Copyright (c) 2015, Core12 Team
 *
 * ----------------------------------------------
 * All Rights Reserved.
 * ----------------------------------------------
 */
namespace sKGroup\M3u\Output;

use sKGroup\M3u\OutputInterface;

/**
 * Class File
 * @package sKGroup\M3u\Output
 */
class File implements OutputInterface
{
    protected $path;
    protected $handle;

    public function __construct()
    {
        $this->path = sys_get_temp_dir() . '/' .uniqid(time() . '_');
        $this->file = new \SplFileObject($this->path, 'w');
    }

    public function __destruct()
    {
        $this->close();

        if (is_file($this->path)) {
            unlink($this->path);
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->path;
    }

    /**
     * @param string $string
     */
    public function append($string)
    {
        $this->getFile()->fwrite($string);
    }

    /**
     * @param $dist
     * @return bool
     */
    public function save($dist)
    {
        $this->close();
        return rename($this->path, $dist);
    }

    /**
     * @return \SplFileObject
     */
    protected function getFile()
    {
        return $this->file;
    }

    /**
     * @return void
     */
    protected function close()
    {
        $this->file = null;
    }
}
