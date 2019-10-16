<?php

namespace WannaBePro\Composer\Plugin\Release\Builder;

/**
 * Simple copy file builder.
 */
class CopyBuilder extends BaseBuilder
{
    protected function getFrom($path, array $config)
    {
        $stream = fopen($path, 'r', null, $this->getContext($config));
        if (array_key_exists('filters', $config)) {
            foreach ($config['filters'] as $filter) {
                stream_filter_append($stream, $filter);
            }
        }

        return $stream;
    }

    protected function getTo($path, array $config)
    {
        return fopen($this->target . DIRECTORY_SEPARATOR . $path, 'w', null, $this->getContext($config));
    }

    protected function getContext(array $config)
    {
        return stream_context_create(array_key_exists('context', $config) ? $config['context'] : []);
    }
}
