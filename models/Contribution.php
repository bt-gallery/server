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
    public $idDeclarant;

    /**
     *
     * @var integer
     */
    public $idParticipant;

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
    public $persons;

    /**
     *
     * @var string
     */
    public $storePath;

    /**
     *
     * @var string
     */
    public $webPath;

    /**
     *
     * @var string
     */
    public $fileName;

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
    public $fileSize;

    /**
     *
     * @var string
     */
    public $thumbStorePath;

    /**
     *
     * @var string
     */
    public $thumbWebPath;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'StairwayToModeration', 'id_contribution', array('alias' => 'StairwayToModeration'));
        $this->hasMany('id', 'Vote', 'id_contribution', array('alias' => 'Vote'));
        $this->belongsTo('id_declarant', 'Declarant', 'id', array('alias' => 'Declarant'));
        $this->belongsTo('id_participant', 'Participant', 'id', array('alias' => 'Participant'));
        $this->belongsTo('moderation', 'ModerationStatus', 'id', array('alias' => 'ModerationStatus'));
        $this->belongsTo('rejection', 'Rejection', 'id', array('alias' => 'Rejection'));
        $this->skipAttributes(array('time'));
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
            'id' => 'idContribution',
            'time' => 'time',
            'id_declarant' => 'idDeclarant',
            'id_participant' => 'idParticipant',
            'name' => 'name',
            'description' => 'description',
            'persons' => 'persons',
            'store_path' => 'storePath',
            'web_path' => 'webPath',
            'file_name' => 'fileName',
            'moderation' => 'moderation',
            'rejection' => 'rejection',
            'category' => 'category',
            'priority' => 'priority',
            'type' => 'type',
            'file_size' => 'fileSize',
            'thumb_store_path' => 'thumbStorePath',
            'thumb_web_path' => 'thumbWebPath'
        );
    }
    public function getParticipant()
    {
       //return Resultset\Simple
        if ($this->idParticipant) {
            return Participant::find("id={$this->idParticipant}");
        }else{
            return false;
        }
    }
    public function getVotes()
    {
        //return Resultset\Simple
        if ($this->idContribution) {
            return Vote::find("idContribution={$this->idContribution}");
        }else{
            return false;
        }
    }

}
