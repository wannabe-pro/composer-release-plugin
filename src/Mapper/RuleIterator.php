<?php

namespace WannaBePro\Composer\Plugin\Release\Mapper;

/**
 * The release mapper rule iterator.
 */
class RuleIterator extends BaseIterator
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
