<?php

class MailTask extends \Phalcon\Cli\Task
{
    public function mainAction()
    {
        echo "\nThis is the default task and the default action \n";
    }

    /**
     * @param array $params
     */
    public function registeredAction(array $params=[])
    {
        $config = $this->getDI()->getService("config")->getDefinition();
        $logger = $this->getDI()->getShared("logger");

        $params = [];

        $mailer = new \Phalcon\Ext\Mailer\Manager($config);

        $message = $mailer->createMessageFromView('register', $params)
            ->to('yourchev@gmail.com', 'OPTIONAL NAME')
            ->subject('Hello world!')
            ->content('Hello world!');

         // Set the Bcc addresses of this message.
         $message->bcc('yurchev_di@mirtv.ru');

         // Send message
        $message->send();

        $logger->addInfo("message sent");
    }
}
