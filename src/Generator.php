<?php
/**
 * Project: php-m3u8
 * File: Generator.php 2015-08-20 19:59
 * ----------------------------------------------
 *
 * @author      Stanislav Kiryukhin <korsar.zn@gmail.com>
 * @copyright   Copyright (c) 2015, Core12 Team
 *
 * ----------------------------------------------
 * All Rights Reserved.
 * ----------------------------------------------
 */
namespace Core12\M3u8;

use Core12\M3u8\Output;

/**
 * Class Generator
 * @package Core12\M3u8
 */
class Generator implements GeneratorInterface
{
    /**
     * @var OutputInterface
     */
    private $outputProvider;

    /**
     * @param OutputInterface|null $output
     */
    public function __construct(OutputInterface $output = null)
    {
        if ($output === null) {
            $output = new Output\File();
        }

        $this->outputProvider = $output;
    }

    /**
     * @param string $tag
     * @param string|array $attributes
     */
    public function tag($tag, $attributes = null)
    {
        if ($attributes !== null) {
            if (is_array($attributes)) {
                $attributes = $this->generateAttributes($attributes);
            } else {
                $attributes = ':' . $attributes;
            }
        }

        $this->string($tag . $attributes);
    }

    /**
     * @param $uri
     * @param int $duration
     * @param null $title
     */
    public function tagMediaSegment($uri, $duration = -1, $title = null)
    {
        $this->tag(static::TAG_EXTINF, [$duration, $title]);
        $this->string($uri);
    }

    /**
     * @param string $str
     */
    public function string($str)
    {
        $this->getOutputProvider()->append($str . PHP_EOL);
    }

    /**
     * @param $comment
     */
    public function comment($comment)
    {
        $this->string('#' . $comment);
    }

    /**
     * @return OutputInterface
     */
    public function getOutputProvider()
    {
        return $this->outputProvider;
    }

    /**
     * @param array $attributes
     * @return null|string
     */
    protected function generateAttributes(array $attributes)
    {
        $attrs = [];
        asort($attributes);

        foreach ($attributes as $key => $value) {
            if ($value) {
                $attrs[] = is_int($key) ? $value : $key . '=' . $value;
            }
        }

        if ($attrs) {
            return ':' . implode(',', $attrs);
        } else {
            return null;
        }
    }
}
