<?php

namespace WannaBePro\Composer\Plugin\Release\Builder;

use Composer\Util\Filesystem;
use Exception;
use WannaBePro\Composer\Plugin\Release\Mapper\FileIterator;
use ZipArchive;

class ZipBuilder extends CopyBuilder
{
    /**
     * @inheritDoc
     */
    public function build(FileIterator $files, $update = false)
    {
        $this->io->write("Build {$this->target}");
        (new Filesystem())->ensureDirectoryExists(dirname($this->target));
        $zip = new ZipArchive();
        $zip->open($this->target, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        foreach ($files as $file) {
            $config = $file->getConfig();
            $source = $file->getFile();
            $from = $this->getFrom($source, $config);
            $conent = fread($from, filesize($source));
            fclose($from);
            $path = $this->getZipPath($file);
            $zip->addFromString($path, $conent);
        }
        $zip->close();
    }

    /**
     * {@inheritDoc}
     */
    protected function getTo($path, array $config)
    {
        throw new Exception('Not suported');
    }

    /**
     * Get relative path for ZIP.
     *
     * @param string $path The path
     *
     * @return string
     */
    protected function getZipPath($path)
    {
        return $this->remove('/', str_replace('\\', '/', $path));
    }

    /**
     * Remove prefix from string.
     *
     * @param string $needle The prefix.
     * @param string $haystack The string.
     *
     * @return string
     */
    protected function remove($needle, $haystack)
    {
        $pos = strpos($haystack, $needle);
        if ($pos === 0) {
            return substr_replace($haystack, '', $pos, strlen($needle));
        }

        return $haystack;
    }
}
