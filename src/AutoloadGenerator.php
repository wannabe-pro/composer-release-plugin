<?php

namespace WannaBePro\Composer\Plugin\Release;

use Composer\Autoload\AutoloadGenerator as BaseAutoloadGenerator;
use Composer\Config;
use Composer\EventDispatcher\EventDispatcher;
use Composer\Installer\InstallationManager;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;

class AutoloadGenerator extends BaseAutoloadGenerator
{
    protected $path;

    public function __construct($path, EventDispatcher $eventDispatcher, IOInterface $io = null)
    {
        $this->path = $path;
        parent::__construct($eventDispatcher, $io);
    }

    public function dump(Config $config, InstalledRepositoryInterface $localRepo, PackageInterface $mainPackage, InstallationManager $installationManager, $targetDir, $scanPsr0Packages = false, $suffix = '')
    {
        $cwd = getcwd();
        chdir($this->path);
        parent::dump($config, $localRepo, $mainPackage, $installationManager, $targetDir, $scanPsr0Packages, $suffix);
        chdir($cwd);
    }
}
