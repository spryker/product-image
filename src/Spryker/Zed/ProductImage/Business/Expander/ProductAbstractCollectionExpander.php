<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\ProductImage\Business\Expander;

use Generated\Shared\Transfer\ProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractCriteriaTransfer;
use Generated\Shared\Transfer\ProductImageSetConditionsTransfer;
use Generated\Shared\Transfer\ProductImageSetCriteriaTransfer;
use Spryker\Zed\ProductImage\Business\Reader\ProductImageSetReaderInterface;

class ProductAbstractCollectionExpander implements ProductAbstractCollectionExpanderInterface
{
    public function __construct(
        protected readonly ProductImageSetReaderInterface $productImageSetReader,
    ) {
    }

    public function expandProductAbstractCollectionWithImageSets(
        ProductAbstractCollectionTransfer $productAbstractCollectionTransfer,
        ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer,
    ): ProductAbstractCollectionTransfer {
        if (!$productAbstractCriteriaTransfer->getProductAbstractRelations()?->getWithImageSets()) {
            return $productAbstractCollectionTransfer;
        }

        $productAbstractIds = [];

        foreach ($productAbstractCollectionTransfer->getProductAbstracts() as $productAbstractTransfer) {
            if ($productAbstractTransfer->getIdProductAbstract() !== null) {
                $productAbstractIds[] = $productAbstractTransfer->getIdProductAbstract();
            }
        }

        if (!$productAbstractIds) {
            return $productAbstractCollectionTransfer;
        }

        $productImageSetCollectionTransfer = $this->productImageSetReader->getAbstractProductImageSetCollection(
            (new ProductImageSetCriteriaTransfer())
                ->setProductImageSetConditions(
                    (new ProductImageSetConditionsTransfer())
                        ->setProductAbstractIds($productAbstractIds),
                ),
        );

        $imageSetsByProductAbstractId = [];

        foreach ($productImageSetCollectionTransfer->getProductImageSets() as $productImageSetTransfer) {
            $imageSetsByProductAbstractId[(int)$productImageSetTransfer->getIdProductAbstract()][] = $productImageSetTransfer;
        }

        foreach ($productAbstractCollectionTransfer->getProductAbstracts() as $productAbstractTransfer) {
            $idProductAbstract = $productAbstractTransfer->getIdProductAbstract();

            foreach ($imageSetsByProductAbstractId[$idProductAbstract] ?? [] as $productImageSetTransfer) {
                $productAbstractTransfer->addImageSet($productImageSetTransfer);
            }
        }

        return $productAbstractCollectionTransfer;
    }
}
