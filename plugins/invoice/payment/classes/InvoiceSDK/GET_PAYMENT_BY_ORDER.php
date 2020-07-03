<?php

namespace invoice\payment\classes\InvoiceSDK;
class GET_PAYMENT_BY_ORDER
{
    /**
     * @var string
     * Order ID
     */
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }


}