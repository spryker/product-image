<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Provider;

use Spryker\Shared\Search\Provider\AbstractIndexClientProvider;

class IndexClientProvider extends AbstractIndexClientProvider
{

    /**
     * @return \Elastica\Index
     */
    public function getClient()
    {
        return $this->createZedClient();
    }

}
