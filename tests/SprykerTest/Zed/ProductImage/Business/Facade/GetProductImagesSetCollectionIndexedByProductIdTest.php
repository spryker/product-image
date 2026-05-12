<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductImageSetTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Facade
 * @group GetProductImagesSetCollectionIndexedByProductIdTest
 * Add your own group annotations below this line
 */
class GetProductImagesSetCollectionIndexedByProductIdTest extends Unit
{
 /**
  * @var \SprykerTest\Zed\ProductImage\ProductImageBusinessTester
  */
    protected $tester;

    /**
     * Verifies that the method returns a map keyed by concrete product ID where:
     * - every requested product ID is present in the result,
     * - products with image sets have the correct number of ProductImageSetTransfer entries,
     * - products without image sets have an empty array.
     *
     * @dataProvider provideGetProductImagesSetCollectionIndexedByProductIdData
     */
    public function testGetProductImagesSetCollectionIndexedByProductIdReturnsCorrectMap(
        int $productCountWithImageSets,
        int $productCountWithoutImageSets,
        int $imageSetsPerProduct,
    ): void {
        // Arrange
        $productsWithSets = [];

        for ($i = 0; $i < $productCountWithImageSets; $i++) {
            $product = $this->tester->haveProduct();

            for ($j = 0; $j < $imageSetsPerProduct; $j++) {
                $this->tester->haveProductImageSet([
                    ProductImageSetTransfer::ID_PRODUCT => $product->getIdProductConcrete(),
                ]);
            }

            $productsWithSets[] = $product;
        }

        $productsWithoutSets = [];

        for ($i = 0; $i < $productCountWithoutImageSets; $i++) {
            $productsWithoutSets[] = $this->tester->haveProduct();
        }

        $allProductIds = array_map(
            static fn ($product) => $product->getIdProductConcrete(),
            array_merge($productsWithSets, $productsWithoutSets),
        );

        // Act
        $result = $this->tester->getFacade()->getProductImagesSetCollectionIndexedByProductId($allProductIds);

        // Assert — result contains exactly one entry per requested product ID
        $this->assertCount(count($allProductIds), $result);

        foreach ($productsWithSets as $product) {
            $idProductConcrete = $product->getIdProductConcrete();

            $this->assertArrayHasKey($idProductConcrete, $result);
            $this->assertCount($imageSetsPerProduct, $result[$idProductConcrete]);

            foreach ($result[$idProductConcrete] as $imageSetTransfer) {
                $this->assertInstanceOf(ProductImageSetTransfer::class, $imageSetTransfer);
            }
        }

        foreach ($productsWithoutSets as $product) {
            $idProductConcrete = $product->getIdProductConcrete();

            $this->assertArrayHasKey($idProductConcrete, $result);
            $this->assertEmpty($result[$idProductConcrete]);
        }
    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function provideGetProductImagesSetCollectionIndexedByProductIdData(): array
    {
        return [
            'empty input returns empty map' => [
                'productCountWithImageSets' => 0,
                'productCountWithoutImageSets' => 0,
                'imageSetsPerProduct' => 0,
            ],
            'single product with one image set' => [
                'productCountWithImageSets' => 1,
                'productCountWithoutImageSets' => 0,
                'imageSetsPerProduct' => 1,
            ],
            'single product with multiple image sets returns all sets' => [
                'productCountWithImageSets' => 1,
                'productCountWithoutImageSets' => 0,
                'imageSetsPerProduct' => 3,
            ],
            'multiple products each with image sets' => [
                'productCountWithImageSets' => 3,
                'productCountWithoutImageSets' => 0,
                'imageSetsPerProduct' => 1,
            ],
            'product without image sets gets empty array in result' => [
                'productCountWithImageSets' => 0,
                'productCountWithoutImageSets' => 1,
                'imageSetsPerProduct' => 0,
            ],
            'mixed: products with and without image sets' => [
                'productCountWithImageSets' => 2,
                'productCountWithoutImageSets' => 2,
                'imageSetsPerProduct' => 1,
            ],
        ];
    }
}
