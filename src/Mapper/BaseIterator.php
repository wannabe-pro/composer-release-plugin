<?php

namespace WannaBePro\Composer\Plugin\Release\Mapper;

use FilterIterator;
use Iterator;
use MultipleIterator;

/**
 * The base iterator.
 */
abstract class BaseIterator extends FilterIterator
{
    /**
     * @var MultipleIterator The inner iterator.
     */
    protected $innerIterator;

    /**
     * @inheritDoc
     *
     * @param Iterator $iterator
     */
    public function __construct(Iterator $iterator)
    {
        $this->innerIterator = new MultipleIterator();
        $this->innerIterator->attachIterator($iterator);
        parent::__construct($this->innerIterator);
    }

    /**
     * @inheritDoc
     *
     * @return MultipleIterator
     */
    public function getInnerIterator()
    {
        return $this->innerIterator;
    }
}
