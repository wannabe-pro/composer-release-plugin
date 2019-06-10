<?php

namespace WannaBePro\Composer\Plugin\Release\Mapper;

use Iterator;

/**
 * The source iterator.
 */
class SourceIterator extends FileIterator
{
    /**
     * @var string The CWD.
     */
    protected $cwd;

    /**
     * @var string The vendor path.
     */
    protected $vendor;

    /**
     * @var string The package path.
     */
    protected $package;

    /**
     * SourceIterator constructor.
     *
     * @param Iterator $iterator The iterator.
     * @param string $vendor The vendor path.
     * @param string $name The package path.
     */
    public function __construct(Iterator $iterator, $vendor, $name)
    {
        $this->cwd = getcwd() . DIRECTORY_SEPARATOR;
        $this->vendor = realpath($vendor) . DIRECTORY_SEPARATOR;
        $this->package = realpath($vendor . DIRECTORY_SEPARATOR . $name) . DIRECTORY_SEPARATOR;
        parent::__construct($iterator);
    }

    /**
     * @inheritDoc
     *
     * @return File
     */
    public function current()
    {
        return new File(
            realpath(parent::current()),
            $this->remove($this->cwd, $this->remove($this->package, parent::current()))
        );
    }

    /**
     * @inheritDoc
     *
     * @return bool
     */
    public function accept()
    {
        return strpos(parent::current(), $this->vendor) !== 0 || strpos(parent::current(), $this->package) === 0;
    }

    /**
     * Remove prefix from string.
     *
     * @param string $needle The prefix.
     * @param string $haystack The string.
     *
     * @return string
     */
    protected function remove($needle, $haystack)
    {
        $pos = strpos($haystack, $needle);
        if ($pos === 0) {
            return substr_replace($haystack, '', $pos, strlen($needle));
        }

        return $haystack;
    }
}
