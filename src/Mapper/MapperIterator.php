<?php

namespace WannaBePro\Composer\Plugin\Release\Mapper;

/**
 * The release mapper iterator.
 */
class MapperIterator extends BaseIterator
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
        return $this->current()->getBuilder()->getTarget();
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
