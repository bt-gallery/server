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
            'id_declarant' => 'idDeclarant',
            'age' => 'age'
        );
    }

    public function getGroup()
    {
        if ($this->age >= 4 and $this->age <= 6) {
            return 1;
        } else if($this->age >= 7 and $this->age <= 12) {
            return 2;
        } else if($this->age >= 13 and $this->age <= 18) {
            return 3;
        }else{
            return;
        }
    }

    public static function getGroupS($age)
    {
        if ($age >= 4 and $age <= 6) {
            return 1;
        } else if($age >= 7 and $age <= 12) {
            return 2;
        } else if($age >= 13 and $age <= 18) {
            return 3;
        }else{
            return;
        }
    }
}
