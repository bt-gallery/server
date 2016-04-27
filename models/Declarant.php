<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

class Declarant extends \Phalcon\Mvc\Model
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
    public $email;

    /**
     *
     * @var string
     */
    public $phone;

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
     * Validations and business logic
     *
     * @return boolean
     */

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'Participant', 'id_declarant', array('alias' => 'Participant'));
        $this->belongsTo('rejection', 'Rejection', 'id', array('alias' => 'Rejection'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Declarant[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Declarant
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
        return 'declarant';
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
            'id' => 'idDeclarant',
            'time' => 'time',
            'name' => 'name',
            'surname' => 'surname',
            'patronymic' => 'patronymic',
            'email' => 'email',
            'phone' => 'phone',
            'moderation' => 'moderation',
            'rejection' => 'rejection'
        );
    }
    public function getParticipants()
    {
        //return Resultset\Simple
        if($this->idDeclarant){
            return Participant::find("idDeclarant={$this->idDeclarant}");
        }else {
            return false;
        }
    }
    public function getContributions()
    {
        //return Resultset\Simple
        if($this->idDeclarant){
            return Contribution::find("idDeclarant={$this->idDeclarant}");
        }else {
            return false;
        }
    }

}
