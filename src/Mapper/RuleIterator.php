<?php

namespace WannaBePro\Composer\Plugin\Release\Mapper;

use FilterIterator;

/**
 * The mapper rules iterator.
 */
class RuleIterator extends FilterIterator
{
    /**
     * @inheritDoc
     *
     * @return Rule
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
        return (string) $this->current();
    }

    /**
     * @inheritDoc
     *
     * @return bool
     */
    public function accept()
    {
        return parent::current() instanceof Rule;
    }
}
