<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CalculationCheckoutConnector\Dependency\Facade;

use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

class CalculationCheckoutConnectorToCalculationBridge implements CalculationCheckoutConnectorToCalculationInterface
{

    /**
     * @var \Spryker\Zed\Calculation\Business\CalculationFacade
     */
    protected $calculationFacade;

    /**
     * @param \Spryker\Zed\Calculation\Business\CalculationFacade $calculationFacade
     */
    public function __construct($calculationFacade)
    {
        $this->calculationFacade = $calculationFacade;
    }

    /**
     * @param CalculableInterface $calculableContainer
     *
     * @return CalculableInterface
     */
    public function recalculate(CalculableInterface $calculableContainer)
    {
        return $this->calculationFacade->recalculate($calculableContainer);
    }

}