<?php

namespace didikala\models;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    public $timestamps = false;
    protected $primaryKey = 'id';

    static public $orders_count = 0;
    static public $itemPerPage = ITEM_PER_PAGE;

    static function setOrdersCount()
    {
        if (User::isUserWriter())
            Order::$orders_count = Order::getWriterOrders($_SESSION['user_id'])->count();
        else
            Order::$orders_count = Order::getAllOrders()->count();
    }

    static function getRevenue($when, $format = true)
    {
        if (User::isUserWriter())
            $revenue = Order::getWriterOrders($_SESSION['user_id'], 0, 0, true);
        else
            $revenue = Order::where('id', '!=', 0);
        
        $revenue = $revenue->where('completed_at', '>=', date('Y-m-d', strtotime("-1 $when")))->sum('total');
        if ($format)
            return number_format($revenue);
        else
            return $revenue;
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public static function getAllOrders($limit = 0, $offset = 0)
    {
        if ($offset > 0)
            return Order::where('status', '!=', 'pending')->orderBy('created_at', 'desc')->limit($limit)->offset($offset)->get();
        if ($limit > 0)
            return Order::where('status', '!=', 'pending')->orderBy('created_at', 'desc')->limit($limit)->get();
        else
            return Order::where('status', '!=', 'pending')->orderBy('created_at', 'desc')->get();
    }

    public static function getWriterOrders($user_id, $limit = 0, $offset = 0, $revenue = false)
    {
        $userProducts_id = Product::where('user_id', $user_id)->pluck('id')->toArray();
        $orders = Order::getAllOrders();
        $userOrders = [];
        foreach ($orders as $order) {
            $orderItems = OrderItem::where('order_id', $order->id)->whereIn('product_id', $userProducts_id)->get();
            foreach ($orderItems as $orderItem) {
                $userOrders[] = $orderItem->order_id;
            }
        }
        if ($revenue)
            return Order::whereIn('id', $userOrders);
        return Order::whereIn('id', $userOrders)->orderBy('created_at', 'desc')->limit($limit)->offset($offset)->get();
    }

    static function getPageCount()
    {
        return ceil(Order::$orders_count / Order::$itemPerPage);
    }

    public static function getItems($order_id)
    {
        return OrderItem::where('order_id', $order_id)->get();
    }

    public static function getOrders($user_id, $limit = 2)
    {
        if ($limit == 0)
            return Order::where('user_id', $user_id)->whereIn('status', ['failed', 'completed'])->orderBy('created_at', 'desc')->get();
        else
            return Order::where('user_id', $user_id)->whereIn('status', ['failed', 'completed'])->orderBy('created_at', 'desc')->limit($limit)->get();
    }

    public static function getOrder($order_id)
    {
        return Order::where('id', $order_id)->first();
    }

    static function getItemCount($id)
    {
        $order = Order::where('user_id', $id)->where('status', 'pending')->first();
        return OrderItem::where('order_id', $order->id)->count();
    }

    static function haveOrder($user_id)
    {
        return Order::where('user_id', $user_id)->where('status', 'pending')->count();
    }

    static function getTrackId($price, $merchant = 'zibal', $url = 'http://didikala.local/cart/complete')
    {
        $headers = [
            'Content-Type: application/json'
        ];

        $data = [
            'merchant' => $merchant,
            'amount' => $price,
            'callbackUrl' => $url
        ];

        $body = json_encode($data);

        $options = [
            'http' => [
                'header' => implode("\r\n", $headers),
                'method' => 'POST',
                'content' => $body,
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ];

        $context = stream_context_create($options);
        $url = 'https://gateway.zibal.ir/v1/request';
        $response = file_get_contents($url, false, $context);
        $response = (object)json_decode($response);
        if ($response) {
            if ($response->result == 100) {
                if ($response->message == "success") {
                    if ($response->trackId)
                        return intval($response->trackId);
                }
            } else if ($response->result == 113) {
                return 'مبلغ تراکنش از سقف میزان تراکنش بیشتر است! لطفا تعداد محصولات را کاهش دهید.';
            }
        }
        return false;
    }

    static function getVerifyPay($trackId, $merchant = 'zibal')
    {
        if ($merchant && is_numeric($trackId) && $trackId > 0) {
            $headers = [
                'Content-Type: application/json'
            ];

            $data = [
                'merchant' => $merchant,
                'trackId' => $trackId
            ];

            $body = json_encode($data);

            $options = [
                'http' => [
                    'header' => implode("\r\n", $headers),
                    'method' => 'POST',
                    'content' => $body,
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ];

            $context = stream_context_create($options);
            $url = 'https://gateway.zibal.ir/v1/verify';
            $response = file_get_contents($url, false, $context);
            $response = (object)json_decode($response);
            if ($response) {
                if ($response->result == 100) {
                    if ($response->message == "success") {
                        if ($response->status == 1) {
                            return (new DateTime($response->paidAt))->format('Y-m-d H:i:s');
                        }
                    }
                }
            }
            return false;
        }
        return false;
    }

    static function getPayUrl($trackId)
    {
        if ($trackId)
            return "https://gateway.zibal.ir/start/$trackId";
    }

    public static function calculateTotals($orderId)
    {
        $orderItems = OrderItem::where('order_id', $orderId)->get();

        $subtotal = 0;
        $total = 0;

        foreach ($orderItems as $item) {
            $itemSubtotal = $item->quantity * $item->price;
            $itemTotal = $item->quantity * $item->sale_price;
            $subtotal += $itemSubtotal;
            $total += $itemTotal;
        }

        Order::where('id', $orderId)->update([
            'subtotal' => $subtotal,
            'total' => $total
        ]);
    }

    public static function getTotal($orderId, $format = true)
    {
        if ($format)
            return number_format(Order::where('id', $orderId)->get()[0]->total);
        else
            return Order::where('id', $orderId)->get()[0]->total;
    }

    public static function getSubTotal($orderId, $format = true)
    {
        if ($format)
            return number_format(Order::where('id', $orderId)->get()[0]->subtotal);
        else
            return Order::where('id', $orderId)->get()[0]->subtotal;
    }

    public static function getProfit($orderId, $format = true)
    {
        if ($format)
            return number_format(Order::getSubTotal($orderId, false) - Order::getTotal($orderId, false));
        else
            return Order::getSubTotal($orderId, false) - Order::getTotal($orderId, false);
    }

    public static function getProfitPercent($orderId, $format = true)
    {
        $profit = Order::getProfit($orderId, false);
        $total = Order::getTotal($orderId, false);
        $subTotal = Order::getSubTotal($orderId, false);
        $average = ($total + $subTotal) / 2;

        $percentProfit = ($profit / $average) * 100;
        if ($format)
            return number_format($percentProfit);
        else
            return $percentProfit;
    }

}