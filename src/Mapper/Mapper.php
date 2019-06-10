<?php

namespace WannaBePro\Composer\Plugin\Release\Mapper;

use Composer\Composer;
use IteratorAggregate;
use RecursiveDirectoryIterator;
use FilesystemIterator;
use OuterIterator;
use RecursiveIteratorIterator;
use WannaBePro\Composer\Plugin\Release\Builder;

/**
 * The release mapper.
 */
class Mapper implements IteratorAggregate
{
    /**
     * @var Builder The release builder.
     */
    protected $builder;

    /**
     * @var RuleIterator The mapper rules.
     */
    protected $rules;

    /**
     * @var Composer The composer instance.
     */
    protected $composer;

    /**
     * Mapper constructor.
     *
     * @param Builder $builder The release builder.
     * @param RuleIterator $rules The mapper rules.
     * @param Composer $composer The composer instance.
     */
    public function __construct(Builder $builder, RuleIterator $rules, Composer $composer)
    {
        $this->builder = $builder;
        $this->rules = $rules;
        $this->composer = $composer;
    }

    /**
     * Get release builder.
     *
     * @return Builder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * Get mapper rules.
     *
     * @return RuleIterator
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Get release composer.
     *
     * @return Composer
     */
    public function getComposer()
    {
        return $this->composer;
    }

    /**
     * @inheritDoc
     *
     * @return TargetIterator
     */
    public function getIterator()
    {
        return new TargetIterator(
            $this,
            new SourceIterator(
                $this->getInnerIterator(),
                $this->composer->getConfig()->get('vendor-dir'),
                $this->builder->getTarget()
            )
        );
    }

    /**
     * Get inner iterator.
     *
     * @return RecursiveIteratorIterator
     */
    protected function getInnerIterator()
    {
        return new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                getcwd(),
                FilesystemIterator::CURRENT_AS_PATHNAME
                | FilesystemIterator::KEY_AS_PATHNAME
                | FilesystemIterator::FOLLOW_SYMLINKS
                | FilesystemIterator::SKIP_DOTS
            )
        );
    }
}
