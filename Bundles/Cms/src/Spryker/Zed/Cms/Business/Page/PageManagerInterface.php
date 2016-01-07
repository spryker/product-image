<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Business\Page;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Cms\Business\Exception\MissingPageException;
use Spryker\Zed\Cms\Business\Exception\MissingTemplateException;
use Spryker\Zed\Cms\Business\Exception\PageExistsException;
use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Spryker\Zed\Url\Business\Exception\MissingUrlException;
use Spryker\Zed\Url\Business\Exception\UrlExistsException;

interface PageManagerInterface
{

    /**
     * @param PageTransfer $page
     *
     * @throws MissingTemplateException
     * @throws MissingPageException
     * @throws MissingUrlException
     * @throws PageExistsException
     *
     * @return PageTransfer
     */
    public function savePage(PageTransfer $page);

    /**
     * @param int $idPage
     *
     * @throws MissingPageException
     *
     * @return SpyCmsPage
     */
    public function getPageById($idPage);

    /**
     * @param SpyCmsPage $page
     *
     * @return PageTransfer
     */
    public function convertPageEntityToTransfer(SpyCmsPage $page);

    /**
     * @param PageTransfer $page
     *
     * @return void
     */
    public function touchPageActive(PageTransfer $page);

    /**
     * @param PageTransfer $page
     * @param string $url
     *
     * @throws UrlExistsException
     *
     * @return UrlTransfer
     */
    public function createPageUrl(PageTransfer $page, $url);

    /**
     * @param PageTransfer $page
     * @param string $url
     * @param LocaleTransfer $localeTransfer
     *
     * @throws UrlExistsException
     *
     * @return UrlTransfer
     */
    public function createPageUrlWithLocale(PageTransfer $page, $url, LocaleTransfer $localeTransfer);

    /**
     * @param PageTransfer $page
     * @param string $url
     *
     * @return UrlTransfer
     */
    public function savePageUrlAndTouch(PageTransfer $page, $url);

    /**
     * @param PageTransfer $page
     * @param CmsBlockTransfer $blockTransfer
     *
     * @return PageTransfer
     */
    public function savePageBlockAndTouch(PageTransfer $page, CmsBlockTransfer $blockTransfer);

}