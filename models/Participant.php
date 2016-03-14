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
    public $id_declarant;

    public function initialize()
    {
        $this->hasMany('idParticipant', 'CompetitiveWork', 'idParticipant');
        $this->belongsTo('idDeclarant', 'Declarant', 'idDeclarant');
    }
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
            'id_declarant' => 'idDeclarant'
        );
    }

}
