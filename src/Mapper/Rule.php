<?php

namespace WannaBePro\Composer\Plugin\Release\Mapper;

/**
 * The release mapper rule.
 *
 * Mapper rule check source file relative path and apply template name for it. If template name not set or false then
 * its exclude file from release. By default template name equivalent source and all files will be excluded if not
 * otherwise.
 *
 * @code
 * {
 *     "#.*#": false,
 *     "#^README.md#": true,
 *     "#(.*)\\.php$#": "$1.inc"
 * }
 * @endCode
 */
class Rule
{
    /**
     * @var string The regexp pattern.
     */
    protected $pattern;

    /**
     * @var string The regexp template.
     */
    protected $result;

    /**
     * @var array The stream config.
     */
    protected $config;

    /**
     * Rule constructor.
     *
     * @param string $pattern The regexp pattern.
     * @param string $result The regexp template.
     * @param array $config The stream config.
     */
    public function __construct($pattern, $result, array $config = [])
    {
        $this->pattern = $pattern;
        $this->result = $result;
        $this->config = $config;
    }

    /**
     * Get regexp pattern.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->pattern;
    }

    /**
     * Apply rule ro file.
     *
     * @param TargetIterator $iterator The target iterator.
     */
    public function apply(TargetIterator $iterator)
    {
        if (is_numeric($this->pattern) && is_file($this->result)) {
            /** @noinspection PhpIncludeInspection */
            require_once $this->result;
        } else {
            $file = $iterator->current();
            $sourcePath = $file->getSource();
            if (preg_match($this->pattern, $sourcePath) === 1) {
                if ($this->result) {
                    $file->setConfig($this->config);
                    if (is_string($this->result)) {
                        $file->setTarget(preg_replace($this->pattern, $this->result, $sourcePath));
                    } else {
                        $file->setTarget($sourcePath);
                    }
                } else {
                    $file->setTarget(null);
                }
            }
        }
    }
}
