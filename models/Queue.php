<?php

class Queue extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_queue;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $data;

    /**
     *
     * @var string
     */
    public $status;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id_queue' => 'id_queue',
            'created_at' => 'created_at',
            'job' => 'job',
            'data' => 'data',
            'status' => 'status'
        );
    }

}
