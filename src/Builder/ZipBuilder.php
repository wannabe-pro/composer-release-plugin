<?php

namespace WannaBePro\Composer\Plugin\Release\Builder;

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
        try {
            $this->install($update);
            $zip = new ZipArchive();
            $zip->open($this->target, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            foreach ($files as $file) {
                $config = $file->getConfig();
                $source = $file->getFile();
                $from = $this->getFrom($source, $config);
                $content = fread($from, filesize($source));
                fclose($from);
                $path = $this->getZipPath($file);
                $zip->addFromString($path, $content);
            }
            $zip->close();
        } catch (Exception $exception) {
            $this->io->writeError($exception->getMessage());
        }
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
