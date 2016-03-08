<?php

class Participant extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_participant;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $surname;

    /**
     *
     * @var string
     */
    public $patronymic;

    /**
     *
     * @var integer
     */
    public $declarant_id_declarant;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id_participant' => 'idParticipant', 
            'name' => 'name', 
            'surname' => 'surname', 
            'patronymic' => 'patronymic', 
            'declarant_id_declarant' => 'idDeclarant'
        );
    }

}
