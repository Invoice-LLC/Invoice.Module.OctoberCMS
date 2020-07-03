<?php namespace Invoice\Payment\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Settings Back-end Controller
 */
class Settings extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
    ];

    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Invoice.Payment', 'payment', 'settings');
    }
}
