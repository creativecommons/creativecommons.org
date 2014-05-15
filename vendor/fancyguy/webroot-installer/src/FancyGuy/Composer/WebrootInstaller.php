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

namespace FancyGuy\Composer;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class WebrootInstaller extends LibraryInstaller {
    
    const INSTALLER_TYPE = 'webroot';
        
    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package) {
        $type = $package->getType();
        
        $prettyName = $package->getPrettyName();
        if (strpos($prettyName, '/') !== false) {
            list($vendor, $name) = explode('/', $prettyName);
        } else {
            $vendor = '';
            $name = $prettyName;
        }
        
        if ($this->composer->getPackage()) {
            $extra = $this->composer->getPackage()->getExtra();
            
            if (!empty($extra['webroot-dir']) && !empty($extra['webroot-package']) && $extra['webroot-package'] === $prettyName) {
                return $extra['webroot-dir'];
            } else {
                throw new \InvalidArgumentException('Sorry only one package can be installed into the configured webroot.');
            }
        } else {
            throw new \InvalidArgumentException('The root package is not configured properly.');
        }
        
    }
    
    /**
     * {@inheritDoc}
     */
    public function supports($packageType) {
        return $packageType === self::INSTALLER_TYPE;
    }
    
}