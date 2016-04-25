<?php

class Participant extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $time;

    /**
     *
     * @var integer
     */
    public $idDeclarant;

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
     * @var string
     */
    public $description;

    /**
     *
     * @var integer
     */
    public $specification;

    /**
     *
     * @var integer
     */
    public $moderation;

    /**
     *
     * @var integer
     */
    public $rejection;

    /**
     *
     * @var integer
     */
    public $team;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'Contribution', 'id_participant', array('alias' => 'Contribution'));
        $this->belongsTo('rejection', 'Rejection', 'id', array('alias' => 'Rejection'));
        $this->belongsTo('id_declarant', 'Declarant', 'id', array('alias' => 'Declarant'));
        $this->belongsTo('specification', 'Specification', 'id', array('alias' => 'Specification'));
        $this->belongsTo('moderation', 'ModerationStatus', 'id', array('alias' => 'ModerationStatus'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Participant[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Participant
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'participant';
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap()
    {
        return array(
            'id' => 'idParticipant',
            'time' => 'time',
            'id_declarant' => 'idDeclarant',
            'name' => 'name',
            'surname' => 'surname',
            'patronymic' => 'patronymic',
            'description' => 'description',
            'year' => 'year',
            'moderation' => 'moderation',
            'rejection' => 'rejection',
            'team' => 'team'
        );
    }
    public function getContributions()
    {
        //return Resultset\Simple
        if($this->idParticipant){
            return Contribution::find("idParticipant={$this->idParticipant}");
        }else {
            return false;
        }
    }
    public function getDeclarant()
    {
        //return Resultset\Simple
        if($this->idDeclarant){
            return Declarant::find("id={$this->idDeclarant}");
        }else {
            return false;
        }
    }

}