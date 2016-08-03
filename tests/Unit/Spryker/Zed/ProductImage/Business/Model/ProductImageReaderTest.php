<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ProductImage\Business\Model;

use Generated\Shared\Transfer\ProductImageSetTransfer;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\ProductImage\Business\Model\Reader;
use Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferGenerator;
use Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainer;
use Codeception\TestCase\Test;

class ProductImageReaderTest extends Test
{


    /**
     * @var \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductImage\Business\Model\ReaderInterface
     */
    protected $reader;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferGeneratorInterface
     */
    protected $transferGenerator;


    protected function setUp()
    {
        $this->reader = new Reader(
            new ProductImageQueryContainer()
        );

        $this->localeFacade =  new LocaleFacade();
    }

    public function testGetProductImagesByProductAbstractId()
    {
        $imageCollection = $this->reader
            ->getProductImagesByProductAbstractId(1);

        $this->assertNotEmpty($imageCollection);
    }

}
