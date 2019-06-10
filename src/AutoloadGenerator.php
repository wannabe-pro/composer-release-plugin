<?php

namespace WannaBePro\Composer\Plugin\Release;

use Composer\Autoload\AutoloadGenerator as BaseAutoloadGenerator;
use Composer\Config;
use Composer\EventDispatcher\EventDispatcher;
use Composer\Installer\InstallationManager;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;

/**
 * The release composer autoload generator.
 */
class AutoloadGenerator extends BaseAutoloadGenerator
{
    /**
     * @var string The release composer path.
     */
    protected $path;

    /**
     * @inheritDoc
     *
     * @param string $path The release composer path.
     * @param EventDispatcher $eventDispatcher The event dispatcher.
     * @param IOInterface|null $io The IO interface.
     */
    public function __construct($path, EventDispatcher $eventDispatcher, IOInterface $io = null)
    {
        $this->path = $path;
        parent::__construct($eventDispatcher, $io);
    }

    /**
     * @inheritDoc
     *
     * @param Config $config The config.
     * @param InstalledRepositoryInterface $localRepo The local repository.
     * @param PackageInterface $mainPackage The main package.
     * @param InstallationManager $installationManager The installation manager.
     * @param string $targetDir The target directory.
     * @param bool $scanPsr0Packages The scan PSR0 packages flag.
     * @param string $suffix The suffix.
     */
    public function dump(
        Config $config,
        InstalledRepositoryInterface $localRepo,
        PackageInterface $mainPackage,
        InstallationManager $installationManager,
        $targetDir,
        $scanPsr0Packages = false,
        $suffix = ''
    )
    {
        $cwd = getcwd();
        chdir($this->path);
        parent::dump($config, $localRepo, $mainPackage, $installationManager, $targetDir, $scanPsr0Packages, $suffix);
        chdir($cwd);
    }
}
