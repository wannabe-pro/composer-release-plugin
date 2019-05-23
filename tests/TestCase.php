<?php

namespace WannaBePro\Composer\Plugin\Release;

use Composer\Factory;
use Composer\Installer;
use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * Test case.
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @var Installer The installer.
     */
    protected $installer;

    /**
     * @var string The CWD.
     */
    protected $cwd;

    /**
     * Set up.
     */
    public function setUp()
    {
        $this->cwd = __DIR__ . '/data';
        $io = new VoidIO();
        $factory = new Factory();
        $composer = $factory->createComposer($io, $this->cwd . '/composer.json', false, $this->cwd);
        $this->installer = Installer::create($io, $composer);
    }
}
