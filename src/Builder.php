<?php

namespace WannaBePro\Composer\Plugin\Release;

use Composer\Composer;
use Composer\Installer;
use Composer\IO\IOInterface;
use Traversable;

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
     * @param Traversable $files The release files, where key is source path and value is target path.
     * @param bool $update The upgrade flag.
     *
     * @return void
     *
     * @code
     * public function build(Traversable $files, $update = false)
     * {
     *     $this->getInstaller($update)->run(); // install composer components
     *     foreach($files as $source => $target) {
     *         $this->io->write("Release file $source to $target");
     *     }
     * }
     * @endcode
     */
    public abstract function build(Traversable $files, $update = false);

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
}
