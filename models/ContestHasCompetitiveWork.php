<?php

class ContestHasCompetitiveWork extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $contest_id_contest;

    /**
     *
     * @var integer
     */
    public $competitive_work_id_competitive_work;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'contest_id_contest' => 'idContest',
            'competitive_work_id_competitive_work' => 'idCompetitiveWork'
        );
    }

}
