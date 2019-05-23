<?php

namespace WannaBePro\Composer\Plugin\Release\Mapper;

use FilterIterator;

/**
 * The release mapper iterator.
 */
class MapperIterator extends FilterIterator
{
    /**
     * @inheritDoc
     *
     * @return Mapper
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
        return $this->current()->getBuilder()->getName();
    }

    /**
     * @inheritDoc
     *
     * @return bool
     */
    public function accept()
    {
        return parent::current() instanceof Mapper;
    }
}
