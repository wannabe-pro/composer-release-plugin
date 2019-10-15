<?php

namespace WannaBePro\Composer\Plugin\Release;

use Composer\Composer;
use Composer\Installer;
use Composer\IO\IOInterface;
use Traversable;
use WannaBePro\Composer\Plugin\Release\Mapper\File;
use WannaBePro\Composer\Plugin\Release\Mapper\FileIterator;

/**
 * The release builder.
 */
abstract class Builder
{
    /**
     * @var string The build name.
     */
    protected $target;

    /**
     * @var Composer The build composer.
     */
    protected $composer;

    /**
     * @var IOInterface The IO interface.
     */
    protected $io;

    /**
     * Builder constructor.
     *
     * @param string $target The target.
     * @param Composer $composer The build composer instance.
     * @param IOInterface $io The IO interface.
     */
    public function __construct($target, Composer $composer, IOInterface $io)
    {
        $this->target = $target;
        $this->composer = $composer;
        $this->io = $io;
    }

    /**
     * Get builder name.
     *
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Build release.
     *
     * @param FileIterator $files The release files, where key is source path and value is target path.
     * @param bool $update The upgrade flag.
     *
     * @return void
     */
    public function build(FileIterator $files, $update = false)
    {
        $this->getInstaller($update)->run();
        try {
            foreach ($files as $file) {
                $config = $file->getConfig();
                $from = $this->getFrom($file->getFile(), $config);
                $to = $this->getTo($this->target . DIRECTORY_SEPARATOR . $file, $config);
                stream_copy_to_stream($from, $to);
                fclose($from);
                fclose($to);
            }
        } catch (Throwable $exception) {
            $this->io->writeError($exception->getMessage());
        }
    }

    /**
     * Get installer for package.
     *
     * @param bool $update The upgrade flag.
     *
     * @return Installer
     */
    protected function getInstaller($update = false)
    {
        $install = Installer::create($this->io, $this->composer);
        $install->setUpdate($update);

        return $install;
    }

    /**
     * Get source stream.
     *
     * @param string $path The source file path.
     * @param array $config Stream config.
     *
     * @return resource
     */
    abstract protected function getFrom($path, array $config);

    /**
     * Get target stream.
     *
     * @param string $path The target file path.
     * @param array $config Stream config.
     *
     * @return resource
     */
    abstract protected function getTo($path, array $config);
}
