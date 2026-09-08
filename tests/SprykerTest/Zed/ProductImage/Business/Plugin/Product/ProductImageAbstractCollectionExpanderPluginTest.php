<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Plugin\Product;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractCriteriaTransfer;
use Generated\Shared\Transfer\ProductAbstractRelationsTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Spryker\Zed\ProductImage\Communication\Plugin\Product\ProductImageAbstractCollectionExpanderPlugin;
use SprykerTest\Zed\ProductImage\ProductImageBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Plugin
 * @group Product
 * @group ProductImageAbstractCollectionExpanderPluginTest
 *
 * Add your own group annotations below this line
 */
class ProductImageAbstractCollectionExpanderPluginTest extends Unit
{
    protected ProductImageBusinessTester $tester;

    public function testExpandAddsImageSetsToProductAbstractWhenWithImageSetsEnabled(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);

        $productAbstractCollectionTransfer = (new ProductAbstractCollectionTransfer())
            ->addProductAbstract((new ProductAbstractTransfer())->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract()));
        $productAbstractCriteriaTransfer = (new ProductAbstractCriteriaTransfer())
            ->setProductAbstractRelations((new ProductAbstractRelationsTransfer())->setWithImageSets(true));

        // Act
        $productAbstractCollectionTransfer = (new ProductImageAbstractCollectionExpanderPlugin())
            ->expand($productAbstractCollectionTransfer, $productAbstractCriteriaTransfer);

        // Assert
        $this->assertGreaterThan(0, $productAbstractCollectionTransfer->getProductAbstracts()->offsetGet(0)->getImageSets()->count());
    }

    public function testExpandDoesNotAddImageSetsWhenWithImageSetsDisabled(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);

        $productAbstractCollectionTransfer = (new ProductAbstractCollectionTransfer())
            ->addProductAbstract((new ProductAbstractTransfer())->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract()));
        $productAbstractCriteriaTransfer = new ProductAbstractCriteriaTransfer();

        // Act
        $productAbstractCollectionTransfer = (new ProductImageAbstractCollectionExpanderPlugin())
            ->expand($productAbstractCollectionTransfer, $productAbstractCriteriaTransfer);

        // Assert
        $this->assertCount(0, $productAbstractCollectionTransfer->getProductAbstracts()->offsetGet(0)->getImageSets());
    }
}
