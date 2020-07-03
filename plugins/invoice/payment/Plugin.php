<?php
namespace invoice\payment;
use Backend;
use Event;
use invoice\payment\classes\helper\PaymentGateway;
use Lovata\OrdersShopaholic\Models\PaymentMethod;

/**
 * Class Plugin
 * @package invoice\payment
 * @author Invoice LLC
 */
class Plugin extends \System\Classes\PluginBase {
    public $require = ['Lovata.Toolbox', 'Lovata.Shopaholic', 'Lovata.OrdersShopaholic'];

    public function boot() {
        Event::listen(PaymentMethod::EVENT_GET_GATEWAY_LIST, function() {
            $arPaymentMethodList = [
                'invoice' => 'Invoice',
            ];

            return $arPaymentMethodList;
        });

        PaymentMethod::extend(function ($obElement) {
            /** @var PaymentMethod $obElement */

            $obElement->addGatewayClass('invoice', PaymentGateway::class);
        });
    }

    public function registerSettings()
    {
        return [
            'invoice-payment-settings' => [
                'label'       => 'Настройки модуля',
                'description' => '',
                'category'    => 'Invoice',
                'icon'        => 'icon-credit-card',
                'class'       => 'invoice\payment\Models\Setting',
                'order'       => 200,
            ],
        ];
    }

}