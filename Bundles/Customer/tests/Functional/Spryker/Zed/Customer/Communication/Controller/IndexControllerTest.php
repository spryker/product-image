<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Customer\Communication\Controller;

use Spryker\Zed\Application\Communication\Plugin\Pimple;
use Spryker\Zed\Customer\Communication\Controller\IndexController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group Spryker
 * @group Zed
 * @group Customer
 * @group Communication
 * @group Controller
 */
class IndexControllerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testIndexAction()
    {
        $request = Request::create('/customer');
        $application = (new Pimple())->getApplication();
        $application['request'] = $request;

        $controller = new IndexController();
        $this->assertInternalType('array', $controller->indexAction());
    }

    /**
     * @return void
     */
    public function testTableAction()
    {
        $request = Request::create('/customer/table');
        $application = (new Pimple())->getApplication();
        $application['request'] = $request;

        $controller = new IndexController();
        $this->assertInstanceOf(JsonResponse::class, $controller->tableAction());
    }

}
