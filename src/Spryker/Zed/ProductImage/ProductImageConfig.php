<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\ProductImage\ProductImageConfig getSharedConfig()
 */
class ProductImageConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @deprecated This method will be removed in the next major version. Product image alternative text will be enabled by default.
     *
     * Specification:
     * - Enable or disable product image alternative text feature.
     *
     * @return bool
     */
    public function isProductImageAlternativeTextEnabled(): bool
    {
        return $this->getSharedConfig()->isProductImageAlternativeTextEnabled();
    }

    /**
     * Specification:
     * - Enables the optional `uuid` column on the `spy_product_image_set` table.
     * - When enabled, the `ProductImageSetUuid` schema folder is merged during `propel:install`.
     * - Consumers relying on product image set UUID (e.g. backend API image set identifiers) require this to be enabled.
     *
     * @api
     */
    public function isProductImageSetUuidEnabled(): bool
    {
        return false;
    }
}
