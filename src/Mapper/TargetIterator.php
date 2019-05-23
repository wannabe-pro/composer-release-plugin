<?php

namespace WannaBePro\Composer\Plugin\Release\Mapper;

use Iterator;

class TargetIterator extends FileIterator
{
    protected $rules;

    public function __construct(Iterator $iterator, RuleIterator $rules)
    {
        parent::__construct($iterator);
        $this->rules = $rules;
    }

    public function accept()
    {
        return parent::accept() && $this->apply();
    }

    protected function apply()
    {
        foreach ($this->rules as $rule) {
            $rule->apply($this->current());
        }

        return strlen($this->current()) > 0;
    }
}
