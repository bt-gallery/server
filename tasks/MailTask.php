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
            ->to($params["declarant"]["email"])
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

    public function formRegisteredQueueAction(array $params=[])
    {
        $db = $this->getDI()->getShared("db");
        $queue = $this->getDI()->getService("queue")->getDefinition();
        $query = "SELECT DISTINCT declarant.id_declarant FROM declarant LEFT JOIN competitive_work ON competitive_work.id_declarant = declarant.id_declarant WHERE competitive_work.bet = 1 ORDER BY `declarant`.`id_declarant`  DESC";
        $result = $db->query($query);
        $result->setFetchMode(Phalcon\Db::FETCH_ASSOC);
        $result = $result->fetchAll($result);

        foreach($result as $currentOne) {
            $declarant = Declarant::findFirst($currentOne["id_declarant"]);
            $competitiveWork = CompetitiveWork::find("idDeclarant={$currentOne["id_declarant"]} AND bet=1");
            foreach($competitiveWork as $currentWork){
                $moderationStack = ModerationStack::findFirst("idCompetitiveWork={$currentWork->idCompetitiveWork}");
                if(!$moderationStack) $queueNum = 0;
                else $queueNum = $moderationStack->queueNum;
                $jobData["queueNum"][$currentWork->idParticipant] = $queueNum;
            }
            $jobData["declarant"] = $declarant->toArray();
            $jobData["participants"] = Participant::find("idDeclarant={$currentOne["id_declarant"]}")->toArray();

            $queue($jobData, Job::MAIL_DECLARANT_REGISTRATION,Status::NEW_ONE);
            unset($jobData["queueNum"]);
        }
    }
}
