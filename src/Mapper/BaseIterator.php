<?php

namespace WannaBePro\Composer\Plugin\Release\Mapper;

use FilterIterator;
use Iterator;
use AppendIterator;

/**
 * The base iterator.
 */
abstract class BaseIterator extends FilterIterator
{
    /**
     * @var AppendIterator The inner iterator.
     */
    protected $innerIterator;

    /**
     * @inheritDoc
     *
     * @param Iterator $iterator
     */
    public function __construct(Iterator $iterator)
    {
        $this->innerIterator = new AppendIterator();
        $this->innerIterator->append($iterator);
        parent::__construct($this->innerIterator);
    }

    /**
     * @inheritDoc
     *
     * @return AppendIterator
     */
    public function getInnerIterator()
    {
        return $this->innerIterator;
    }
}
