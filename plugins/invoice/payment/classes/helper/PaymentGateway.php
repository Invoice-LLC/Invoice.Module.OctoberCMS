<?php

namespace invoice\payment\classes\helper;

require_once "plugins/invoice/payment/classes/InvoiceSDK/common/ORDER.php";

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use invoice\payment\classes\InvoiceSDK\common\INVOICE_ORDER;
use invoice\payment\classes\InvoiceSDK\common\SETTINGS;
use invoice\payment\classes\InvoiceSDK\CREATE_PAYMENT;
use invoice\payment\classes\InvoiceSDK\CREATE_TERMINAL;
use invoice\payment\classes\InvoiceSDK\GET_TERMINAL;
use invoice\payment\classes\InvoiceSDK\RestClient;
use invoice\payment\Models\Setting;
use Lovata\OrdersShopaholic\Models\Order;
use Lovata\OrdersShopaholic\Models\Status;
use Monolog\Logger;
use Redirect;
use Lovata\OrdersShopaholic\Classes\Helper\AbstractPaymentGateway;

class PaymentGateway extends AbstractPaymentGateway
{
    protected $sResponseMessage;
    protected $errors = [];

    protected $url = "";

    /**
     * @inheritDoc
     */
    protected function preparePurchaseData()
    {
        // TODO: Implement preparePurchaseData() method.
    }

    /**
     * @inheritDoc
     */
    protected function validatePurchaseData()
    {
        // TODO: Implement validatePurchaseData() method.
    }

    /**
     * @inheritDoc
     */
    protected function sendPurchaseData()
    {
        // TODO: Implement sendPurchaseData() method.
    }

    /**
     * @inheritDoc
     */
    protected function processPurchaseResponse()
    {
        // TODO: Implement processPurchaseResponse() method.
    }

    /**
     * @inheritDoc
     */
    public function getResponse(): array
    {
        return  [];
    }

    /**
     * @inheritDoc
     */
    public function getRedirectURL(): string
    {
        return $this->url;
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        setcookie("cart_session_id", "", time() - 3600, "/");
        setcookie("shopaholic_cart_id", "", time() - 3600, "/");

        Db::commit();
        $this->url = $this->createPayment($this->obOrder->total_price, $this->obOrder->secret_key);

        if (empty($this->errors)) {
            $this->bIsRedirect = true;
        } else {
            return $this->errors[0];
        }

        return "";
    }

    /**
     * @return CREATE_TERMINAL
     * @return GET_TERMINAL
     */
    public function getTerminal()
    {
        if (!file_exists("invoice_tid")) file_put_contents("invoice_tid", "");
        $tid = file_get_contents("invoice_tid");

        $terminal = new GET_TERMINAL();
        $terminal->alias =  $tid;
        $info = (new RestClient(Setting::get("login"), Setting::get("api_key")))->GetTerminal($terminal);

        if ($tid == null or empty($tid) || $info->id == null || $info->id != $terminal->alias) {
            $request = new CREATE_TERMINAL();
            $request->name = Setting::get("default_terminal_name");
            $request->description = "OctoberCMS terminal";
            $request->type = "dynamical";
            $request->defaultPrice = 1;

            $response = (new RestClient(Setting::get("login"), Setting::get("api_key")))->CreateTerminal($request);

            if ($response == null) return $this->addError("Terminal Error");
            if (isset($response->error)) return $this->addError("Terminal Error");

            $tid = $response->id;
            file_put_contents("invoice_tid", $tid);
        }
        return $tid;
    }

    /**
     * @return CREATE_PAYMENT
     */
    public function createPayment($amount, $orderId)
    {
        $create_payment = new CREATE_PAYMENT();
        $create_payment->order = $this->getOrder($amount, $orderId);
        $create_payment->settings = $this->getSettings();
        $create_payment->receipt = $this->getReceipt();

        $response = (new RestClient(Setting::get("login"), Setting::get("api_key")))->CreatePayment($create_payment);

        if ($response == null or isset($response->error))  return $this->addError("Payment error");

        return $response->payment_url;
    }

    public function addError($error)
    {
        array_push($this->errors, $error);
    }

    /**
     * @return INVOICE_ORDER
     */
    public function getOrder($amount, $orderId)
    {
        $order = new INVOICE_ORDER();
        $order->amount = $amount;
        $order->id = $orderId . "-" . bin2hex(random_bytes(5));

        return $order;
    }

    /**
     * @return SETTINGS
     */
    public function getSettings()
    {
        $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];

        $settings = new SETTINGS();
        $settings->terminal_id = $this->getTerminal();
        $settings->fail_url = $url;
        $settings->success_url = $url;

        return $settings;
    }

    public function getReceipt()
    {
        $receipt = array();

        return $receipt;
    }
}
