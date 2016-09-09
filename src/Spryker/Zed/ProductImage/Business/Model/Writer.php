<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Model;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface;

class Writer implements WriterInterface
{

    /**
     * @var \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface
     */
    protected $productImageContainer;

    /**
     * @param \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface $productImageContainer
     */
    public function __construct(ProductImageQueryContainerInterface $productImageContainer)
    {
        $this->productImageContainer = $productImageContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer
     */
    public function persistProductImage(ProductImageTransfer $productImageTransfer)
    {
        $query = $this->productImageContainer
            ->queryProductImage()
            ->filterByIdProductImage($productImageTransfer->getIdProductImage());

        $productImageEntity = $query->findOne();
        if (!$productImageEntity) {
            $productImageEntity = new SpyProductImage();
        }

        $id = $productImageEntity->getIdProductImage();
        $productImageEntity->fromArray($productImageTransfer->toArray());
        $productImageEntity->setIdProductImage($id);
        $productImageEntity->save();

        $productImageTransfer->setIdProductImage($productImageEntity->getIdProductImage());

        return $productImageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @throws \Exception
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    public function persistProductImageSet(ProductImageSetTransfer $productImageSetTransfer)
    {
        $query = $this->productImageContainer
            ->queryProductImageSet()
            ->filterByIdProductImageSet($productImageSetTransfer->getIdProductImageSet());

        $productImageSetEntity = $query->findOne();
        if (!$productImageSetEntity) {
            $productImageSetEntity = new SpyProductImageSet();
        }

        if ((int)$productImageSetTransfer->getIdProductAbstract() === 0 && (int)$productImageSetTransfer->getIdProduct()) {
            throw new \Exception('ImageSet has no product assigned');
        }

        $this->productImageContainer->getConnection()->beginTransaction();
        try {
            $productImageSetEntity->fromArray($productImageSetTransfer->toArray());
            $productImageSetEntity->setFkProductAbstract($productImageSetTransfer->getIdProductAbstract());
            $productImageSetEntity->setFkProduct($productImageSetTransfer->getIdProduct());
            if ($productImageSetTransfer->getLocale() instanceof LocaleTransfer) {
                $productImageSetEntity->setFkLocale($productImageSetTransfer->getLocale()->getIdLocale());
            }
            $productImageSetEntity->save();

            $productImageSetTransfer->setIdProductImageSet(
                $productImageSetEntity->getIdProductImageSet()
            );

            $updatedImageCollection = [];
            foreach ($productImageSetTransfer->getProductImages() as $imageTransfer) {
                $imageTransfer = $this->persistProductImage($imageTransfer);
                $this->persistProductImageRelation($productImageSetTransfer->getIdProductImageSet(), $imageTransfer->getIdProductImage());

                $updatedImageCollection[] = $imageTransfer;
            }

            $productImageSetTransfer->setProductImages(
                new \ArrayObject($updatedImageCollection)
            );

            $this->productImageContainer->getConnection()->commit();

            return $productImageSetTransfer;

        } catch (\Exception $e) {
            $this->productImageContainer->getConnection()->beginTransaction();
            throw $e;
        }
    }

    /**
     * @param int $idProductImageSet
     * @param int $idProductImage
     * @param int|null $order
     *
     * @return int
     */
    public function persistProductImageRelation($idProductImageSet, $idProductImage, $order = null)
    {
        $query = $this->productImageContainer
            ->queryProductImageSetToProductImage()
            ->filterByFkProductImageSet($idProductImageSet)
            ->filterByFkProductImage($idProductImage);

        $productImageRelationEntity = $query->findOneOrCreate();
        $productImageRelationEntity->setSortOrder((int)$order);
        $productImageRelationEntity->save();

        $productImageRelationEntity->setIdProductImageSetToProductImage(
            $productImageRelationEntity->getIdProductImageSetToProductImage()
        );

        return $productImageRelationEntity->getIdProductImageSetToProductImage();
    }

}
