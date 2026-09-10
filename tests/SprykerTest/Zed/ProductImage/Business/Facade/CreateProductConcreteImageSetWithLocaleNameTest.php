<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Facade
 * @group CreateProductConcreteImageSetWithLocaleNameTest
 * Add your own group annotations below this line
 */
class CreateProductConcreteImageSetWithLocaleNameTest extends AbstractProductImageFacadeTest
{
    protected const string LOCALE_NAME_FOR_TEST = 'ab_CD';

    protected const string UNKNOWN_LOCALE_NAME = 'zz_ZZ';

    public function testCreateProductConcreteImageSetCollectionResolvesLocaleByName(): void
    {
        // Arrange
        $localeTransfer = $this->getAvailableLocaleTransfer();
        $productConcreteTransfer = $this->createProductConcreteTransfer();

        $productImageTransfer = (new ProductImageTransfer())
            ->setExternalUrlSmall(static::URL_SMALL)
            ->setExternalUrlLarge(static::URL_LARGE);

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setName(static::SET_NAME)
            ->setIdProduct($this->productConcreteEntity->getIdProduct())
            ->setLocale(
                (new LocaleTransfer())->setLocaleName($localeTransfer->getLocaleNameOrFail()),
            )
            ->addProductImage($productImageTransfer);

        $productConcreteTransfer->addImageSet($productImageSetTransfer);

        // Act
        $this->productImageFacade->createProductConcreteImageSetCollection($productConcreteTransfer);

        // Assert
        $productImageSetEntity = $this->queryContainer
            ->queryImageSetByProductId($this->productConcreteEntity->getIdProduct())
            ->filterByName(static::SET_NAME)
            ->filterByFkLocale($localeTransfer->getIdLocale())
            ->findOne();

        $this->assertNotNull($productImageSetEntity, 'Image set should be persisted with resolved locale fkLocale.');
        $this->assertSame($localeTransfer->getIdLocale(), $productImageSetEntity->getFkLocale());
    }

    public function testCreateProductConcreteImageSetCollectionWithIdLocaleStillWorks(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_NAME_FOR_TEST]);
        $productConcreteTransfer = $this->createProductConcreteTransfer();

        $productImageTransfer = (new ProductImageTransfer())
            ->setExternalUrlSmall(static::URL_SMALL)
            ->setExternalUrlLarge(static::URL_LARGE);

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setName(static::SET_NAME)
            ->setIdProduct($this->productConcreteEntity->getIdProduct())
            ->setLocale($localeTransfer)
            ->addProductImage($productImageTransfer);

        $productConcreteTransfer->addImageSet($productImageSetTransfer);

        // Act
        $this->productImageFacade->createProductConcreteImageSetCollection($productConcreteTransfer);

        // Assert
        $productImageSetEntity = $this->queryContainer
            ->queryImageSetByProductId($this->productConcreteEntity->getIdProduct())
            ->filterByName(static::SET_NAME)
            ->filterByFkLocale($localeTransfer->getIdLocale())
            ->findOne();

        $this->assertNotNull($productImageSetEntity, 'Image set should be persisted when idLocale is provided directly.');
        $this->assertSame($localeTransfer->getIdLocale(), $productImageSetEntity->getFkLocale());
    }

    public function testCreateProductConcreteImageSetCollectionWithEmptyLocalePersistsAsLocaleIndependent(): void
    {
        // Arrange
        $productConcreteTransfer = $this->createProductConcreteTransfer();

        $productImageTransfer = (new ProductImageTransfer())
            ->setExternalUrlSmall(static::URL_SMALL)
            ->setExternalUrlLarge(static::URL_LARGE);

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setName(static::SET_NAME)
            ->setIdProduct($this->productConcreteEntity->getIdProduct())
            ->setLocale(new LocaleTransfer())
            ->addProductImage($productImageTransfer);

        $productConcreteTransfer->addImageSet($productImageSetTransfer);

        // Act
        $this->productImageFacade->createProductConcreteImageSetCollection($productConcreteTransfer);

        // Assert
        $productImageSetEntity = $this->queryContainer
            ->queryImageSetByProductId($this->productConcreteEntity->getIdProduct())
            ->filterByName(static::SET_NAME)
            ->findOne();

        $this->assertNotNull($productImageSetEntity, 'Locale-independent image set should be persisted.');
        $this->assertNull($productImageSetEntity->getFkLocale());
    }

    public function testCreateProductConcreteImageSetCollectionThrowsExceptionWhenLocaleNameNotFound(): void
    {
        // Arrange
        $productConcreteTransfer = $this->createProductConcreteTransfer();

        $productImageTransfer = (new ProductImageTransfer())
            ->setExternalUrlSmall(static::URL_SMALL)
            ->setExternalUrlLarge(static::URL_LARGE);

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setName(static::SET_NAME)
            ->setIdProduct($this->productConcreteEntity->getIdProduct())
            ->setLocale(
                (new LocaleTransfer())->setLocaleName(static::UNKNOWN_LOCALE_NAME),
            )
            ->addProductImage($productImageTransfer);

        $productConcreteTransfer->addImageSet($productImageSetTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->productImageFacade->createProductConcreteImageSetCollection($productConcreteTransfer);
    }

    public function testCreateProductConcreteImageSetCollectionPersistsCorrectlyWhenAllImageSetsHaveIdLocale(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_NAME_FOR_TEST]);
        $productConcreteTransfer = $this->createProductConcreteTransfer();

        $productImageTransfer = (new ProductImageTransfer())
            ->setExternalUrlSmall(static::URL_SMALL)
            ->setExternalUrlLarge(static::URL_LARGE);

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setName(static::SET_NAME_DE)
            ->setIdProduct($this->productConcreteEntity->getIdProduct())
            ->setLocale($localeTransfer)
            ->addProductImage($productImageTransfer);

        $productConcreteTransfer->addImageSet($productImageSetTransfer);

        // Act
        $this->productImageFacade->createProductConcreteImageSetCollection($productConcreteTransfer);

        // Assert
        $productImageSetEntity = $this->queryContainer
            ->queryImageSetByProductId($this->productConcreteEntity->getIdProduct())
            ->filterByName(static::SET_NAME_DE)
            ->filterByFkLocale($localeTransfer->getIdLocale())
            ->findOne();

        $this->assertNotNull($productImageSetEntity, 'Image set should be persisted when all image sets already have idLocale.');
        $this->assertSame($localeTransfer->getIdLocale(), $productImageSetEntity->getFkLocale());
    }

    protected function getAvailableLocaleTransfer(): LocaleTransfer
    {
        $localeFacade = $this->tester->getLocator()->locale()->facade();

        return $localeFacade->getLocale((string)current($localeFacade->getAvailableLocales()));
    }
}
