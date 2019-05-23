<?php

namespace WannaBePro\Composer\Plugin\Release;

use Traversable;
use Throwable;

/**
 * Simple copy file builder.
 */
class CopyFileBuilder extends Builder
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
                $realTo = $this->name . DIRECTORY_SEPARATOR . $to;
                mkdir(dirname($realTo), 0777, true);
                copy($from, $realTo);
            }
        } catch (Throwable $exception) {
            $this->io->writeError($exception->getMessage());
        }
    }
}
