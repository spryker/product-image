<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\ProductImage\Business\Product\Validator;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionResponseTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductImage\Persistence\ProductImageRepositoryInterface;

class ProductImageSetValidator implements ProductImageSetValidatorInterface
{
    public function __construct(
        protected readonly ProductImageRepositoryInterface $productImageRepository,
    ) {
    }

    public function validateProductConcreteCollection(
        ProductConcreteCollectionRequestTransfer $productConcreteCollectionRequestTransfer,
        ProductConcreteCollectionResponseTransfer $productConcreteCollectionResponseTransfer
    ): ProductConcreteCollectionResponseTransfer {
        if (!$this->productImageRepository->isProductImageSetUuidSupported()) {
            return $productConcreteCollectionResponseTransfer;
        }

        $uuidsByEntity = [];
        $productIdsByEntity = [];

        foreach ($productConcreteCollectionRequestTransfer->getProducts() as $productConcreteTransfer) {
            $sku = (string)$productConcreteTransfer->getSku();

            foreach ($this->extractReferencedUuids($productConcreteTransfer) as $uuid) {
                $uuidsByEntity[$sku][$uuid] = true;
            }

            if (isset($uuidsByEntity[$sku])) {
                $productIdsByEntity[$sku] = $productConcreteTransfer->getIdProductConcrete();
            }
        }

        if ($uuidsByEntity === []) {
            return $productConcreteCollectionResponseTransfer;
        }

        $ownedUuids = $this->productImageRepository->getProductImageSetUuidsGroupedByIdProduct(
            array_values(array_filter($productIdsByEntity)),
        );

        foreach ($uuidsByEntity as $sku => $uuids) {
            $idProductConcrete = $productIdsByEntity[$sku] ?? null;
            $ownedByEntity = $idProductConcrete !== null ? ($ownedUuids[$idProductConcrete] ?? []) : [];

            foreach (array_diff(array_keys($uuids), $ownedByEntity) as $unknownUuid) {
                $productConcreteCollectionResponseTransfer->addError($this->createError((string)$sku, (string)$unknownUuid));
            }
        }

        return $productConcreteCollectionResponseTransfer;
    }

    /**
     * @return list<string>
     */
    protected function extractReferencedUuids(ProductConcreteTransfer $productConcreteTransfer): array
    {
        $uuids = [];

        foreach ($productConcreteTransfer->getImageSets() as $productImageSetTransfer) {
            $uuid = $productImageSetTransfer->getUuid();

            if ($uuid !== null && $uuid !== '') {
                $uuids[] = $uuid;
            }
        }

        return $uuids;
    }

    protected function createError(?string $entityIdentifier, string $unknownUuid): ErrorTransfer
    {
        return (new ErrorTransfer())
            ->setEntityIdentifier($entityIdentifier)
            ->setMessage(sprintf('Image set with UUID "%s" does not exist for this product.', $unknownUuid));
    }
}
