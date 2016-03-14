<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

class Declarant extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_declarant;

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

    public function initialize()
    {
        $this->hasMany('idDeclarant', 'Participant', 'idParticipant');
        $this->hasMany('idDeclarant', 'CompetitiveWork', 'idDeclarant');
    }
    /**
     * Validations and business logic
     */
    public function validation()
    {

        $this->validate(
            new Email(
                array(
                    'field'    => 'email',
                    'required' => true,
                )
            )
        );
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id_declarant' => 'idDeclarant', 
            'name' => 'name', 
            'surname' => 'surname', 
            'patronymic' => 'patronymic', 
            'email' => 'email', 
            'phone' => 'phone'
        );
    }

}
