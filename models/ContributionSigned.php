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
     * @var integer
     */
    public $contributionIdDeclarant;

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
     * @var string
     */
    public $thumbStorePath;

    /**
     *
     * @var string
     */
    public $thumbWebPath;

    /**
     *
     * @var integer
     */
    public $idParticipant;

    /**
     *
     * @var string
     */
    public $participantTime;

    /**
     *
     * @var integer
     */
    public $participantIdDeclarant;

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
    public $year;

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
            'id' => 'idContributionSigned',
            'contribution_time' => 'contributionTime',
            'contribution_id_declarant' => 'contributionIdDeclarant',
            'contribution_name' => 'contributionName',
            'contribution_description' => 'contributionDescription',
            'persons' => 'persons',
            'store_path' => 'storePath',
            'web_path' => 'webPath',
            'file_name' => 'file_name',
            'contribution_moderation' => 'contributionModeration',
            'contribution_rejection' => 'contributionRejection',
            'category' => 'category',
            'priority' => 'priority',
            'type' => 'type',
            'file_size' => 'fileSize',
            'thumb_store_path' => 'thumbStorePath',
            'thumb_web_path' => 'thumbWebPath',
            'id_participant' => 'idParticipant',
            'participant_time' => 'participantTime',
            'participant_id_declarant' => 'participantIdDeclarant',
            'participant_name' => 'participantName',
            'surname' => 'surname',
            'patronymic' => 'patronymic',
            'participant_description' => 'participantDescription',
            'year' => 'year',
            'participant_moderation' => 'participantModeration',
            'participant_rejection' => 'participantRejection',
            'team' => 'team'
        );
    }

}
