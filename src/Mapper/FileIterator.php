<?php

namespace WannaBePro\Composer\Plugin\Release\Mapper;

/**
 * The release files iterator.
 */
class FileIterator extends BaseIterator
{
    /**
     * @inheritDoc
     *
     * @return File
     */
    public function current()
    {
        return parent::current();
    }

    /**
     * @inheritDoc
     *
     * @return string
     */
    public function key()
    {
        return $this->current()->getFile();
    }

    /**
     * @inheritDoc
     *
     * @return bool
     */
    public function accept()
    {
        return parent::current() instanceof File;
    }
}
