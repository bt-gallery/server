<?php

class Vote extends \Phalcon\Mvc\Model
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
    public $ip;

    /**
     *
     * @var string
     */
    public $agent;

    /**
     *
     * @var integer
     */
    public $idContribution;

    /**
     *
     * @var string
     */
    public $hash;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('id_contribution', 'Contribution', 'id', array('alias' => 'Contribution'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Vote[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Vote
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
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
            'id' => 'id',
            'time' => 'time',
            'ip' => 'ip',
            'agent' => 'agent',
            'id_contribution' => 'idContribution',
            'hash' => 'hash'
        );
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'vote';
    }
    public function getContribution()
    {
        //return Resultset\Simple
        if ($this->idContribution) {
            return Contribution::find("id={$this->idContribution}");
        }else{
            return false;
        }
    }
    public function check()
    {
        
    }

}
