<?php

class Address extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $address;

    /**
     *
     * @var string
     */
    public $country;

    /**
     *
     * @var string
     */
    public $subject;

    /**
     *
     * @var string
     */
    public $area;

    /**
     *
     * @var string
     */
    public $city;

    /**
     *
     * @var string
     */
    public $street;

    /**
     *
     * @var string
     */
    public $building;

    /**
     *
     * @var string
     */
    public $appartment;

    /**
     *
     * @var string
     */
    public $zip_code;

    /**
     *
     * @var integer
     */
    public $declarant_id_declarant;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'address' => 'address', 
            'country' => 'country', 
            'subject' => 'subject', 
            'area' => 'area', 
            'city' => 'city', 
            'street' => 'street', 
            'building' => 'building', 
            'appartment' => 'appartment', 
            'zip_code' => 'zipCode', 
            'declarant_id_declarant' => 'idDeclarant'
        );
    }

}
