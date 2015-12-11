<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Yves\Kernel;

use Spryker\Shared\Kernel\AbstractLocatorLocator;
use Spryker\Shared\Kernel\BundleProxy;
use Spryker\Shared\Kernel\TransferLocator;
use Spryker\Client\Kernel\ClientLocator;

class Locator extends AbstractLocatorLocator
{

    /**
     * @return BundleProxy
     */
    protected function getBundleProxy()
    {
        $bundleProxy = new BundleProxy($this);
        $bundleProxy
            ->addLocator(new PluginLocator())
            ->addLocator(new ClientLocator());

        return $bundleProxy;
    }

}
