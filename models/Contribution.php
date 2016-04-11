<?php

class Contribution extends \Phalcon\Mvc\Model
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
    public $description;

    /**
     *
     * @var string
     */
    public $store_path;

    /**
     *
     * @var string
     */
    public $web_path;

    /**
     *
     * @var string
     */
    public $file_name;

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
    public $category;

    /**
     *
     * @var integer
     */
    public $priority;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $file_size;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'Vote', 'id_contribution', array('alias' => 'Vote'));
        $this->belongsTo('id_participant', 'Participant', 'id', array('alias' => 'Participant'));
        $this->belongsTo('moderation', 'ModerationStatus', 'id', array('alias' => 'ModerationStatus'));
        $this->belongsTo('rejection', 'Rejection', 'id', array('alias' => 'Rejection'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Contribution[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Contribution
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
            'id_participant' => 'idParticipant',
            'name' => 'name',
            'description' => 'description',
            'store_path' => 'storePath',
            'web_path' => 'webPath',
            'file_name' => 'fileName',
            'moderation' => 'moderation',
            'rejection' => 'rejection',
            'category' => 'category',
            'priority' => 'priority',
            'type' => 'type',
            'file_size' => 'fileSize'
        );
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contribution';
    }

}
