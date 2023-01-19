<?php

namespace invoice\payment\classes\InvoiceSDK;

use invoice\payment\classes\InvoiceSDK\common\INVOICE_ORDER;
use invoice\payment\classes\InvoiceSDK\common\SETTINGS;

class CREATE_PAYMENT
{
    /**
     * @var INVOICE_ORDER
     */
    public $order;
    /**
     * @var SETTINGS
     */

    public $settings;
    /**
     * @var array
     */
    public $custom_parameters;
    /**
     * @var array(ITEM)
     */
    public $receipt;

    /**
     * Optional fields
     * @var $mail string
     * @var $phone string
     */
    public $mail;
    public $phone;
}
