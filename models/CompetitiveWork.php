<?php

class CompetitiveWork extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_competitive_work;

    /**
     *
     * @var string
     */
    public $store_path;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id_competitive_work' => 'idCompetitiveWork', 
            'store_path' => 'storePath',
            'web_path' => 'webPath',
            'file_name' => 'fileName'
        );
    }
}
