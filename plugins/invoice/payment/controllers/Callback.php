<?php


namespace Invoice\Payment\Controllers;


use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use invoice\payment\Models\Setting;
use Lovata\OrdersShopaholic\Models\Order;
use Lovata\OrdersShopaholic\Models\PaymentMethod;

class Callback extends Controller
{
    public function index()
    {
        $postData = file_get_contents('php://input');
        $notification = json_decode($postData, true);

        $type = $notification["notification_type"];
        $id = strstr($notification["order"]["id"], "-", true);

        $signature = $notification["signature"];

        if ($signature != $this->getSignature($notification["id"], $notification["status"], Setting::get("api_key"))) {
            return "Wrong signature";
        }

        if ($type == "pay") {
            if ($notification["status"] == "successful") {
                $this->pay($id);
                return "payment successful";
            }
            if ($notification["status"] == "error") {
                $this->error($id);
                return "payment failed";
            }
        }

        return "null";
    }

    public function pay($id)
    {
        $order = $this->getOrder($id);
        Db::table('lovata_orders_shopaholic_orders')->where('id', $order->id)->update(['status_id' => $this->getSuccessStatus($order)]);
    }

    public function error($id)
    {
        $order = $this->getOrder($id);
        Db::table('lovata_orders_shopaholic_orders')->where('id', $order->id)->update(['status_id' => $this->getErrorStatus($order)]);
    }

    /**
     * @param $order \Lovata\OrdersShopaholic\Models\Order
     * @return int
     */
    public function getErrorStatus($order)
    {
        /**
         *@var $pm PaymentMethod
         */
        $pm = $this->getPaymentMethod($order->payment_method_id);
        return $pm->fail_status_id;
    }

    /**
     * @param $order \Lovata\OrdersShopaholic\Models\Order
     * @return int
     */
    public function getSuccessStatus($order)
    {
        /**
         *@var $pm PaymentMethod
         */
        $pm = $this->getPaymentMethod($order->payment_method_id);
        return $pm->after_status_id;
    }

    public function getPaymentMethod($id)
    {
        $pm = Db::table('lovata_orders_shopaholic_payment_methods')->where('id', $id)->first();
        if ($pm == null or empty($pm)) {
            die("Invalid order");
        }
        return $pm;
    }

    /**
     * @param $id
     * @return \Lovata\OrdersShopaholic\Models\Order
     */
    public function getOrder($id)
    {
        $order = Db::table('lovata_orders_shopaholic_orders')->where('secret_key', $id)->first();
        if ($order == null or empty($order)) {
            die("not found");
        }
        return $order;
    }

    public function getSignature($id, $status, $key)
    {
        return md5($id . $status . $key);
    }
}
