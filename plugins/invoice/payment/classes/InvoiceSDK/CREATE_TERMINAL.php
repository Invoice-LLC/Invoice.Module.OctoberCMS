<?php

namespace invoice\payment\classes\InvoiceSDK;

class CREATE_TERMINAL
{
    /**
     * @var string
     */
    public $alias;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $description;
    /**
     * @var string{"statical", "dynamical"}
     */
    public $type;
    /**
     * @var double
     */
    public $defaultPrice;
    /**
     * @var boolean
     */
    public $canComments;
}
