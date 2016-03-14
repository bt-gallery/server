<?php

class ModerationStack extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_moderation_stack;

    /**
     *
     * @var integer
     */
    public $id_competitive_work;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id_moderation_stack' => 'idModerationStack',
            'id_competitive_work' => 'idCompetitiveWork',
            'queue_num' => 'queueNum',
            'status' => 'status'
        );
    }

    public function initQueueNum()
    {
        $queueNum = $this->count([
            "conditions" => "status=0",
            "order" => "idModerationStack ASC"
        ]);

        $this->queueNum = $queueNum;
        
        if (!$this->save()) {
            $result["error"] = array_map(
                function ($message) {
                    return $message->getMessage();
                }, $this->getMessages()
                );
        } else {
            $result["success"] = $this->toArray();
        }

        return $result;
    } 

}
