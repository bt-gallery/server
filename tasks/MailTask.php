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
        $saver = $this->getDI()->getService("saver")->getDefinition();
        $logger = $this->getDI()->getShared("mailLogger");

        $mailer = new \Phalcon\Ext\Mailer\Manager($config);

        $queue = Queue::findFirst("job=".Job::MAIL_DECLARANT_REGISTRATION." AND status=".Status::NEW_ONE);

        if(!$queue) {
            $logger->addInfo("no job");
            return;
        } else {
            $logger->addInfo("processing queue ", ["queue" => $queue]);
        }

        $params = unserialize($queue->toArray()['data']);

        $message = $mailer->createMessageFromView('register', $params)
            ->to($params["email"])
            ->subject('Конкурс рисунка');
        $message->bcc('d.yurchev@mail.ru');
         // Send message
        $result = $message->send();
        if($result) {
            $logger->addInfo("message sent", ["queue ID" => $queue->id_queue]);
            $queue->status = Status::DONE;
            $saver($queue);
        }    
        else {
            $logger->addCritical("mailer fails", ["queue ID" => $queue->id_queue]); 
        }

        return;
    }
}
