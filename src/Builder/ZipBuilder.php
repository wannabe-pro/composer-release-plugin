<?php

namespace WannaBePro\Composer\Plugin\Release\Builder;

use ZipArchive;

class ZipBuilder extends CopyBuilder
{
    /**
     * Archive.
     *
     * @var ZipArchive
     */
    protected $archive;

    /**
     * Close archive.
     */
    public function __destruct()
    {
        if (isset($this->archive)) {
            $this->archive->close();
        }
    }

    /**
     * Get archive.
     *
     * @return ZipArchive
     */
    protected function getArchive()
    {
        if (empty($this->archive)) {
            $this->archive = new ZipArchive();
            $this->archive->open($this->target, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        }

        return $this->archive;
    }

    /**
     * {@inheritDoc}
     */
    protected function getTo($path, array $config)
    {
        $archive = $this->getArchive();
        $path = $this->getArchivePath($path);
        $archive->addFile($path);
        return $archive->getStream($path);
    }

    /**
     * Get relative path for ZIP.
     *
     * @param string $path The path
     *
     * @return string
     */
    protected function getArchivePath($path)
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
