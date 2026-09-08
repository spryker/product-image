<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\ProductImage\Communication\Plugin\Product;

use Generated\Shared\Transfer\ProductConcreteCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteCollectionCreateValidatorPluginInterface;

/**
 * @method \Spryker\Zed\ProductImage\Business\ProductImageBusinessFactory getBusinessFactory()
 */
class ProductImageSetExistsProductConcreteCollectionCreateValidatorPlugin extends AbstractPlugin implements ProductConcreteCollectionCreateValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates the image set UUIDs referenced in `ProductConcreteTransfer.imageSets`.
     * - Resolves the image set UUIDs owned by the referenced concrete products with a single query.
     * - Adds an error per concrete product referencing an image set UUID it does not own.
     * - Returns the response unchanged when no concrete product references an image set UUID.
     * - Returns the response unchanged when the optional `spy_product_image_set.uuid` column is not installed.
     *
     * @api
     */
    public function validate(
        ProductConcreteCollectionRequestTransfer $productConcreteCollectionRequestTransfer,
        ProductConcreteCollectionResponseTransfer $productConcreteCollectionResponseTransfer
    ): ProductConcreteCollectionResponseTransfer {
        return $this->getBusinessFactory()
            ->createProductImageSetValidator()
            ->validateProductConcreteCollection($productConcreteCollectionRequestTransfer, $productConcreteCollectionResponseTransfer);
    }
}
