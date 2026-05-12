<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Reader;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToGlossaryFacadeInterface;

class GlossaryReader implements GlossaryReaderInterface
{
    /**
     * @var array<string, array<string, string|null>>
     */
    protected static array $localeCache = [];

    public function __construct(protected ProductImageToGlossaryFacadeInterface $glossaryFacade)
    {
    }

    /**
     * @param array<int, array<string>> $glossaryKeysIndexedByIdLocale
     * @param array<int, \Generated\Shared\Transfer\LocaleTransfer> $localeTransfersIndexedByIdLocale
     *
     * @return array<int, array<string, \Generated\Shared\Transfer\TranslationTransfer>>
     */
    public function getTranslationTransfersIndexedByIdLocaleAndGlossaryKey(
        array $glossaryKeysIndexedByIdLocale,
        array $localeTransfersIndexedByIdLocale
    ): array {
        $translationTransfers = $this->getTranslationTransfers(
            $glossaryKeysIndexedByIdLocale,
            $localeTransfersIndexedByIdLocale,
        );

        return $this->indexTranslationTransfersByIdLocaleAndGlossaryKey($translationTransfers);
    }

    /**
     * @param array<string> $glossaryKeys
     * @param string $localeName
     *
     * @return array<string, string|null>
     */
    public function getTranslationsIndexedByGlossaryKey(array $glossaryKeys, string $localeName): array
    {
        $uncachedKeys = array_values(array_filter(
            $glossaryKeys,
            fn (string $key) => !array_key_exists($key, static::$localeCache[$localeName] ?? []),
        ));

        if ($uncachedKeys !== []) {
            foreach ($uncachedKeys as $key) {
                static::$localeCache[$localeName][$key] = null;
            }

            $translationTransfers = $this->glossaryFacade->getTranslationsByGlossaryKeysAndLocaleTransfers(
                $uncachedKeys,
                [(new LocaleTransfer())->setLocaleName($localeName)],
            );

            foreach ($translationTransfers as $translationTransfer) {
                if (!$translationTransfer->getIsActive()) {
                    continue;
                }

                static::$localeCache[$localeName][$translationTransfer->getGlossaryKeyOrFail()->getKey()] = $translationTransfer->getValue();
            }
        }

        $translationsIndexedByGlossaryKey = [];

        foreach ($glossaryKeys as $key) {
            if (static::$localeCache[$localeName][$key] !== null) {
                $translationsIndexedByGlossaryKey[$key] = static::$localeCache[$localeName][$key];
            }
        }

        return $translationsIndexedByGlossaryKey;
    }

    /**
     * @param array<int, array<string>> $glossaryKeysIndexedByIdLocale
     * @param array<int, \Generated\Shared\Transfer\LocaleTransfer> $localeTransfersIndexedByIdLocale
     *
     * @return array<\Generated\Shared\Transfer\TranslationTransfer>
     */
    protected function getTranslationTransfers(
        array $glossaryKeysIndexedByIdLocale,
        array $localeTransfersIndexedByIdLocale
    ): array {
        $translationTransfers = [];

        foreach ($glossaryKeysIndexedByIdLocale as $idLocale => $glossaryKeys) {
            $translationTransfers[] = $this->glossaryFacade
                ->getTranslationsByGlossaryKeysAndLocaleTransfers($glossaryKeys, [$localeTransfersIndexedByIdLocale[$idLocale]]);
        }

        return array_merge(...$translationTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\TranslationTransfer> $translationTransfers
     *
     * @return array<int, array<string, \Generated\Shared\Transfer\TranslationTransfer>>
     */
    protected function indexTranslationTransfersByIdLocaleAndGlossaryKey(array $translationTransfers): array
    {
        $translationTransfersIndexedByIdLocaleAndGlossaryKey = [];

        foreach ($translationTransfers as $translationTransfer) {
            $glossaryKey = $translationTransfer->getGlossaryKeyOrFail()->getKey();
            $translationTransfersIndexedByIdLocaleAndGlossaryKey[(int)$translationTransfer->getFkLocale()][$glossaryKey] = $translationTransfer;
        }

        return $translationTransfersIndexedByIdLocaleAndGlossaryKey;
    }
}
