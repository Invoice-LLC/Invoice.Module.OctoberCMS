<?php
namespace invoice\payment\classes\InvoiceSDK;

class GET_PAYMENT
{
    /**
     * @var string
     * Payment ID
     */
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
}