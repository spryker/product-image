<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ProductImageSetCollectionTransfer;
use Generated\Shared\Transfer\ProductImageSetCriteriaTransfer;
use Spryker\Zed\ProductImage\Persistence\ProductImageRepositoryInterface;

class ProductImageSetReader implements ProductImageSetReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductImage\Persistence\ProductImageRepositoryInterface
     */
    protected ProductImageRepositoryInterface $productImageRepository;

    public function __construct(ProductImageRepositoryInterface $productImageRepository)
    {
        $this->productImageRepository = $productImageRepository;
    }

    public function getConcreteProductImageSetCollection(
        ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
    ): ProductImageSetCollectionTransfer {
        $productImageSetCollectionTransfer = $this->productImageRepository
            ->getConcreteProductImageSetCollection($productImageSetCriteriaTransfer);

        return $this->expandProductImageSetWithProductImages($productImageSetCollectionTransfer);
    }

    public function getAbstractProductImageSetCollection(
        ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
    ): ProductImageSetCollectionTransfer {
        $productImageSetCollectionTransfer = $this->productImageRepository
            ->getAbstractProductImageSetCollection($productImageSetCriteriaTransfer);

        return $this->expandProductImageSetWithProductImages($productImageSetCollectionTransfer);
    }

    protected function expandProductImageSetWithProductImages(
        ProductImageSetCollectionTransfer $productImageSetCollectionTransfer
    ): ProductImageSetCollectionTransfer {
        $productImageSetTransfersIndexedByProductImageSetId = $this->productImageRepository
            ->getProductImagesByProductSetIds($this->extractProductImageSetIds($productImageSetCollectionTransfer));

        foreach ($productImageSetCollectionTransfer->getProductImageSets() as $productImageSetTransfer) {
            $productImageTransfers = $productImageSetTransfersIndexedByProductImageSetId[$productImageSetTransfer->getIdProductImageSet()] ?? null;

            if ($productImageTransfers) {
                $productImageSetTransfer->setProductImages(new ArrayObject($productImageTransfers));
            }
        }

        return $productImageSetCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetCollectionTransfer $productImageSetCollectionTransfer
     *
     * @return list<int>
     */
    protected function extractProductImageSetIds(ProductImageSetCollectionTransfer $productImageSetCollectionTransfer): array
    {
        $productImageIds = [];
        foreach ($productImageSetCollectionTransfer->getProductImageSets() as $productImageSetTransfer) {
            $productImageIds[] = $productImageSetTransfer->getIdProductImageSet();
        }

        return $productImageIds;
    }
}
