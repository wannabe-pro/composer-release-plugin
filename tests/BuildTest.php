<?php

namespace WannaBePro\Composer\Plugin\Release;

class BuildTest extends TestCase
{
    public function testBuild()
    {
        $this->installer->run();
        $autoload = $this->cwd . '/build/vendor/autoload.php';
        $this->assertFileExists($autoload, 'Autoload will be exists');
        include $autoload;
        $packageClass = '/Test/Package';
        $subPackageClass = '/Test/SubPackage';
        $this->assertTrue(class_exists($packageClass, true), 'Test package will be exists');
        $this->assertTrue(class_exists($subPackageClass, true), 'Test sub package will be exists');
    }
}
