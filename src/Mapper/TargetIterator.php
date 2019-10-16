<?php

namespace WannaBePro\Composer\Plugin\Release\Mapper;

/**
 * The target iterator.
 */
class TargetIterator extends FileIterator
{
    /**
     * @var Mapper The mapper.
     */
    protected $mapper;

    public function __construct(Mapper $mapper, SourceIterator $iterator)
    {
        $this->mapper = $mapper;
        parent::__construct($iterator);
    }

    public function getMapper()
    {
        return $this->mapper;
    }

    public function accept()
    {
        return parent::accept() && (parent::current() instanceof FilteredFile || $this->apply());
    }

    protected function apply()
    {
        foreach ($this->mapper->getRules() as $rule) {
            $rule->apply($this);
        }

        return strlen($this->current()) > 0;
    }
}
