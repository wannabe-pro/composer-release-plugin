<?php

namespace WannaBePro\Composer\Plugin\Release\Copy;

use Traversable;
use Throwable;
use WannaBePro\Composer\Plugin\Release\Builder as BaseBuilder;
use WannaBePro\Composer\Plugin\Release\Mapper\File;

/**
 * Simple copy file builder.
 */
class Builder extends BaseBuilder
{
    protected function getFrom($path, array $config)
    {
        $stream = fopen($path, 'w', null, $this->getContext($config));

        if (array_key_exists('filters', $config)) {
            foreach ($config['filters'] as $filter) {
                stream_filter_append($stream, $filter);
            }
        }

        return $stream;
    }

    protected function getTo($path, array $config)
    {
        return fopen($path, 'w', null, $this->getContext($config));
    }

    protected function getContext(array $config)
    {
        if (array_key_exists('context', $config)) {
            return stream_context_create($config['context']);
        }

        return null;
    }
}
