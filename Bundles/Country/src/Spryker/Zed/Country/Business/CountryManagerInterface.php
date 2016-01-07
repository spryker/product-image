<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Country\Business;

use Generated\Shared\Transfer\CountryTransfer;
use Spryker\Zed\Country\Business\Exception\MissingCountryException;

interface CountryManagerInterface
{

    /**
     * @param string $iso2code
     *
     * @return bool
     */
    public function hasCountry($iso2code);

    /**
     * @param string $iso2code
     * @param array $countryData
     *
     * @return int
     */
    public function createCountry($iso2code, array $countryData);

    /**
     * @param CountryTransfer $countryTransfer
     *
     * @return int
     */
    public function saveCountry(CountryTransfer $countryTransfer);

    /**
     * @param string $iso2code
     *
     * @throws MissingCountryException
     *
     * @return int
     */
    public function getIdCountryByIso2Code($iso2code);

}