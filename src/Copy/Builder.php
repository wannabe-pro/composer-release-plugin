<?php

namespace WannaBePro\Composer\Plugin\Release\Copy;

use Traversable;
use Throwable;
use WannaBePro\Composer\Plugin\Release\Builder as BaseBuilder;

/**
 * Simple copy file builder.
 */
class Builder extends BaseBuilder
{
    /**
     * @inheritDoc
     *
     * @throws \Exception
     */
    public function build(Traversable $files, $update = false)
    {
        $this->getInstaller($update)->run();
        try {
            foreach ($files as $from => $to) {
                $realTo = $this->target . DIRECTORY_SEPARATOR . $to;
                mkdir(dirname($realTo), 0777, true);
                copy($from, $realTo);
            }
        } catch (Throwable $exception) {
            $this->io->writeError($exception->getMessage());
        }
    }
}
