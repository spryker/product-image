<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\FlashMessenger\Business\Model;

use Generated\Shared\Transfer\FlashMessagesTransfer;
use Generated\Shared\Transfer\MessageTransfer;

class InMemoryMessageTray extends BaseMessageTray implements MessageTrayInterface
{

    /**
     * @var FlashMessagesTransfer
     */
    protected static $messages;

    /**
     * @param MessageTransfer $message
     *
     * @return void
     */
    public function addSuccessMessage(MessageTransfer $message)
    {
        self::getFlashMessagesTransfer()->addSuccessMessage(
            $this->translate(
                $message->getValue(),
                $message->getParameters()
            )
        );
    }

    /**
     * @param MessageTransfer $message
     *
     * @return void
     */
    public function addInfoMessage(MessageTransfer $message)
    {
        self::getFlashMessagesTransfer()->addInfoMessage(
            $this->translate(
                $message->getValue(),
                $message->getParameters()
            )
        );
    }

    /**
     * @param MessageTransfer $message
     *
     * @return void
     */
    public function addErrorMessage(MessageTransfer $message)
    {
        self::getFlashMessagesTransfer()->addErrorMessage(
             $this->translate(
               $message->getValue(),
               $message->getParameters()
            )
        );
    }

    /**
     * @return FlashMessagesTransfer
     */
    public function getMessages()
    {
        return self::$messages;
    }

    /**
     * @return FlashMessagesTransfer
     */
    protected static function getFlashMessagesTransfer()
    {
        if (self::$messages === null) {
            self::$messages = new FlashMessagesTransfer();
        }

        return self::$messages;
    }

}