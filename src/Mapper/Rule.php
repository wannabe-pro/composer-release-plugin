<?php

namespace WannaBePro\Composer\Plugin\Release\Mapper;

/**
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
     * Rule constructor.
     *
     * @param string $pattern The regexp pattern.
     * @param string $result The regexp template.
     */
    public function __construct($pattern, $result)
    {
        $this->pattern = $pattern;
        $this->result = $result;
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
     * @param File $file The file.
     */
    public function apply(File $file)
    {
        $sourcePath = $file->getSource();
        if (preg_match($this->pattern, $sourcePath) === 1)
        {
            if ($this->result) {
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
