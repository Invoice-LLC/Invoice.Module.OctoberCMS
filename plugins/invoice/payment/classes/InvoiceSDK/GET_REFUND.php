<?php
namespace invoice\payment\classes\InvoiceSDK;

class GET_REFUND
{
    /**
     * @var string
     * Refund ID
     */
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

}