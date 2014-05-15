<?php

/*
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * 
 *    Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 * 
 *    Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 * 
 *    Neither the name of FancyGuy Technologies nor the names of its
 *    contributors may be used to endorse or promote products derived from this
 *    software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 * 
 * Copyright © 2013, FancyGuy Technologies
 * All rights reserved.
 */
namespace FancyGuy\Composer\Test;

use FancyGuy\Composer\WebrootInstaller;
use Composer\Package\RootPackage;
use Composer\Package\Package;
use Composer\Util\Filesystem;
use Composer\Composer;
use Composer\Config;

class WebrootInstallerTest extends TestCase {
    
    /**
     * @var \Composer\Composer
     */
    private $composer;
    
    /**
     * @var \Composer\Util\Filesystem
     */
    private $fs;
    
    /**
     * @var \Composer\Config
     */
    private $config;
    
    /**
     * @var string
     */
    private $vendorDir;
    
    /**
     * @var string
     */
    private $binDir;
    
    /**
     * @var \Composer\Downloader\DownloadManager
     */
    private $dm;
    
    /**
     * @var \Composer\Repository\InstalledRepositoryInterface
     */
    private $repository;
    
    /**
     * @var \Composer\IO\IOInterface
     */
    private $io;
    
    /**
     * setUp
     * 
     * @return void
     */
    public function setUp() {
        
        $this->fs = new Filesystem();
        
        $this->composer = new Composer();
        
        $this->config = new Config();
        $this->composer->setConfig($this->config);
        
        $this->vendorDir = realpath(sys_get_temp_dir()) . DIRECTORY_SEPARATOR . 'baton-test-vendor';
        $this->ensureDirectoryExistsAndClear($this->vendorDir);
        
        $this->binDir = realpath(sys_get_temp_dir()) . DIRECTORY_SEPARATOR . 'baton-test-bin';
        $this->ensureDirectoryExistsAndClear($this->binDir);
        
        $this->config->merge(array(
            'config'    => array(
                'vendor-dir'    => $this->vendorDir,
                'bin-dir'       => $this->binDir
            )
        ));
        
        $this->dm = $this->getMockBuilder('Composer\Downloader\DownloadManager')
                    ->disableOriginalConstructor()
                    ->getMock();
        $this->composer->setDownloadManager($this->dm);
        
        $this->repository = $this->getMock('Composer\Repository\InstalledRepositoryInterface');
        $this->io = $this->getMock('Composer\IO\IOInterface');
        
    }
    
    /**
     * tearDown
     * 
     * @return void
     */
    public function tearDown() {
        $this->fs->removeDirectory($this->vendorDir);
        $this->fs->removeDirectory($this->binDir);
    }
    
    /**
     * testSupports
     * 
     * @return void
     * 
     * @dataProvider dataForTestSupport
     */
    public function testSupports($type, $expected) {
        $installer = new WebrootInstaller($this->io, $this->composer);
        $this->assertSame($expected, $installer->supports($type), sprintf('Failed to show support for %s', $type));
    }
    
    public function dataForTestSupport() {
        return array(
            array(WebrootInstaller::INSTALLER_TYPE, true)
        );
    }
    
    /**
     * testWebrootInstallPath
     */
    public function testWebrootInstallPath() {
        $installer = new WebrootInstaller($this->io, $this->composer);
        $package = new Package('fancyguy/webroot-package', '1.0.0', '1.0.0');
        $package->setType('webroot');
        
        $consumerPackage = new RootPackage('foo/bar', '1.0.0', '1.0.0');
        $this->composer->setPackage($consumerPackage);
        $consumerPackage->setExtra(array(
            'webroot-dir'       => 'content',
            'webroot-package'   => 'fancyguy/webroot-package'
        ));
        
        $result = $installer->getInstallPath($package);
        $this->assertEquals('content', $result);
    }
    
    /**
     * testGetWebrootConfigurationException
     * 
     * @return void
     * 
     * @expectedException \InvalidArgumentException
     */
    public function testGetWebrootConfigurationException() {
        $installer = new WebrootInstaller($this->io, $this->composer);
        $package = new Package('fancyguy/webroot-package', '1.0.0', '1.0.0');
        $package->setType('webroot');
        
        $result = $installer->getInstallPath($package);
    }
    
    /**
     * testGetMultipleWebrootPackagesException
     * 
     * @return void
     * 
     * @expectedException \InvalidArgumentException
     */
    public function testGetMultipleWebrootPackagesException() {
        $installer = new WebrootInstaller($this->io, $this->composer);
        $package1 = new Package('fancyguy/webroot-package', '1.0.0', '1.0.0');
        $package1->setType('webroot');
        
        $package2 = new Package('fancyguy/another-webroot-package', '1.0.0', '1.0.0');
        $package2->setType('webroot');
        
        $consumerPackage = new RootPackage('foo/bar', '1.0.0', '1.0.0');
        $this->composer->setPackage($consumerPackage);
        $consumerPackage->setExtra(array(
            'webroot-dir'       => 'content',
            'webroot-package'   => 'fancyguy/webroot-package'
        ));
        
        $consumerPackage->setRequires(array($package1, $package2));
        
        $result1 = $installer->getInstallPath($package1);
        $result2 = $installer->getInstallPath($package2);
    }
    
}
