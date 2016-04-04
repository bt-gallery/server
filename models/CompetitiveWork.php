<?php

class CompetitiveWork extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $idCompetitiveWork;

    /**
     *
     * @var string
     */
    public $storePath;

    public function initialize()
    {
        $this->hasMany('idCompetitiveWork', 'ModerationStack', 'idCompetitiveWork');
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id_competitive_work' => 'idCompetitiveWork', 
            'store_path' => 'storePath',
            'web_path' => 'webPath',
            'file_name' => 'fileName',
            'id_participant' => 'idParticipant',
            'id_declarant' => 'idDeclarant',
            'bet' => 'bet',
            'moderation' => 'moderation',
            'priority' => 'priority'
        );
    }
}
