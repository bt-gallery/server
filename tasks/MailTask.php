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
    public function testAction(array $params)
    {
        echo sprintf('hello %s', $params[0]) . PHP_EOL;
        echo sprintf('best regards, %s', $params[1]) . PHP_EOL;

        $config = $this->getDI()->getService("config")->getDefinition();
        $logger = $this->getDI()->getService("logger")->getDefinition();

        $mailer = new \Phalcon\Ext\Mailer\Manager($config);

        $message = $mailer->createMessage()
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
