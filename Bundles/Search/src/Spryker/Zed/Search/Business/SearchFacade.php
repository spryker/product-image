<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Search\Business;

use Elastica\Document;
use Elastica\Response;
use Spryker\Shared\Kernel\Messenger\MessengerInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method SearchBusinessFactory getFactory()
 */
class SearchFacade extends AbstractFacade
{

    /**
     * @param MessengerInterface $messenger
     *
     * @return void
     */
    public function install(MessengerInterface $messenger)
    {
        $this->getFactory()->createSearchInstaller($messenger)->install();
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getFactory()->createSearch()->getTotalCount();
    }

    /**
     * @return array
     */
    public function getMetaData()
    {
        return $this->getFactory()->createSearch()->getMetaData();
    }

    /**
     * @return Response
     */
    public function delete()
    {
        return $this->getFactory()->createSearch()->delete();
    }

    /**
     * @param string $key
     * @param string $type
     *
     * @return Document
     */
    public function getDocument($key, $type)
    {
        return $this->getFactory()->createSearch()->getDocument($key, $type);
    }

}