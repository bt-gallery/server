<?php

class ParticipantHasCompetitiveWork extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $participant_id_participant;

    /**
     *
     * @var integer
     */
    public $competitive_work_id_competitive_work;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'participant_id_participant' => 'idParticipant', 
            'competitive_work_id_competitive_work' => 'idCompetitiveWork'
        );
    }

}
