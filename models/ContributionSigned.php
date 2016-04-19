<?php

class ContributionSigned extends \Phalcon\Mvc\Model
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
    public $contributionTime;

    /**
     *
     * @var string
     */
    public $contributionName;

    /**
     *
     * @var string
     */
    public $contributionDescription;

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
    public $contributionModeration;

    /**
     *
     * @var integer
     */
    public $contributionRejection;

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
     * @var integer
     */
    public $idParticipant;

    /**
     *
     * @var integer
     */
    public $age;

    /**
     *
     * @var integer
     */
    public $year;

    /**
     *
     * @var string
     */
    public $participantTime;

    /**
     *
     * @var integer
     */
    public $idDeclarant;

    /**
     *
     * @var string
     */
    public $participantName;

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
    public $participantDescription;

    /**
     *
     * @var integer
     */
    public $specification;

    /**
     *
     * @var integer
     */
    public $participantModeration;

    /**
     *
     * @var integer
     */
    public $participantRejection;

    /**
     *
     * @var integer
     */
    public $team;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contribution_signed';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContributionSigned[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContributionSigned
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
            'contribution_time' => 'contributionTime',
            'contribution_name' => 'contributionName',
            'contribution_description' => 'contributionDescription',
            'store_path' => 'storePath',
            'web_path' => 'webPath',
            'file_name' => 'fileName',
            'contribution_moderation' => 'contributionModeration',
            'contribution_rejection' => 'contributionRejection',
            'category' => 'category',
            'priority' => 'priority',
            'type' => 'type',
            'file_size' => 'fileSize',
            'id_participant' => 'idParticipant',
            'age' => 'age',
            'year' => 'year',
            'participant_time' => 'participantTime',
            'id_declarant' => 'idDeclarant',
            'participant_name' => 'participantName',
            'surname' => 'surname',
            'patronymic' => 'patronymic',
            'participant_description' => 'participantDescription',
            'specification' => 'specification',
            'participant_moderation' => 'participantModeration',
            'participant_rejection' => 'participantRejection',
            'team' => 'team'
        );
    }
    public function getVotes()
    {
        //return Resultset\Simple
        if ($this->id) {
            return Vote::find("idContribution={$this->id}");
        }else{
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
