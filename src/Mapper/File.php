<?php

namespace WannaBePro\Composer\Plugin\Release\Mapper;

/**
 * The release file.
 */
class File
{
    /**
     * @var string The source file real path.
     */
    protected $file;

    /**
     * @var string The source file relative path.
     */
    protected $source;

    /**
     * @var string The target file relative path.
     */
    protected $target;

    /**
     * @var array The stream config.
     */
    protected $config;

    /**
     * File constructor.
     *
     * @param string $file The source file real path.
     * @param string $source The source file relative path.
     * @param array $config The stream config.
     */
    public function __construct($file, $source, array $config = [])
    {
        $this->file = $file;
        $this->source = $source;
        $this->config = $config;
    }

    /**
     * Get target file relative path.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->target;
    }

    /**
     * Get source file real path.
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Get source file relative path.
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set target file relative path.
     *
     * @param string $target The target file relative path.
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * Set stream config.
     *
     * @param array $config The stream config.
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get stream config.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }
}
