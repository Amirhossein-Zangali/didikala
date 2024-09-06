<?php

namespace didikala\controllers;

use didikala\libraries\Controller;
use didikala\models\Product;
use didikala\models\OrderItem;
use didikala\models\Order;

require_once "../app/bootstrap.php";

if (!isset($_SESSION['user_id']))
    redirect('pages/login');

class Cart extends Controller
{
    public $order_id = 0;

    public function index()
    {
        $userId = $_SESSION['user_id'];

        if (!$userId)
            redirect('pages/login');

        $order = Order::where('user_id', $userId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($order) {
            $items = $order->items()->get();

            foreach ($items as $item) {
                $item->price = Product::getPrice($item->product_id, false);
                $item->sale_price = Product::getSalePrice($item->product_id, false);
                $item->save();
            }

            $data = [
                'items' => $items,
                'order' => $order,
            ];

            Order::calculateTotals($order->id);
        } else
            $data = [];

        $this->view('cart/index', $data);
    }

    public function address()
    {
        $userId = $_SESSION['user_id'];

        if (!$userId)
            redirect('pages/login');

        $order = Order::where('user_id', $userId)
            ->where('status', 'pending')->orderBy('created_at', 'desc')
            ->first();

        if (!$order)
            redirect('cart');

        $items = $order->items()->get();

        foreach ($items as $item) {
            $item->price = Product::getPrice($item->product_id, false);
            $item->sale_price = Product::getSalePrice($item->product_id, false);
            $item->save();
        }

        $data = [
            'items' => $items,
            'order' => $order,
        ];

        Order::calculateTotals($order->id);
        $this->view('cart/address', $data);
    }

    public function pay()
    {
        $userId = $_SESSION['user_id'];

        if (!$userId)
            redirect('pages/login');

        $order = Order::where('user_id', $userId)
            ->where('status', 'pending')->orderBy('created_at', 'desc')
            ->first();

        if (!$order)
            redirect('cart');

        $items = $order->items()->get();

        foreach ($items as $item) {
            $item->price = Product::getPrice($item->product_id, false);
            $item->sale_price = Product::getSalePrice($item->product_id, false);
            $item->save();
        }

        $data = [
            'items' => $items,
            'order' => $order,
        ];

        Order::calculateTotals($order->id);
        $this->view('cart/pay', $data);
    }

    public function complete()
    {
        $userId = $_SESSION['user_id'];

        if (!$userId)
            redirect('pages/login');

        $order = Order::where('user_id', $userId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$order)
            $order = Order::where('user_id', $userId)
                ->where('status', 'failed')->orWhere('status', 'completed')
                ->orderBy('created_at', 'desc')
                ->first();

        if (!$order)
            redirect('cart');

        $items = $order->items()->get();

        foreach ($items as $item) {
            $item->price = Product::getPrice($item->product_id, false);
            $item->sale_price = Product::getSalePrice($item->product_id, false);
            $item->save();
        }

        $data = [
            'items' => $items,
            'order' => $order,
        ];

        Order::calculateTotals($order->id);
        $this->view('cart/complete', $data);
    }

    public function add($productId)
    {
        $userId = $_SESSION['user_id'];

        if (!$userId)
            redirect('pages/login');

        $order = Order::where('user_id', $userId)->where('status', 'pending')->orderBy('created_at', 'desc')
            ->first();

        if (!$order) {
            $order = new Order();
            $order->user_id = $userId;
            $order->save();
        }

        $item = OrderItem::where('order_id', $order->id)
            ->where('product_id', $productId)
            ->first();

        if ($item) {
            $item->quantity += 1;
            $item->save();
        } else {
            $item = new OrderItem();
            $item->order_id = $order->id;
            $item->product_id = $productId;
            $item->quantity = 1;
            $item->price = Product::getPrice($productId, false);
            $item->sale_price = Product::getSalePrice($productId, false);
            $item->save();
        }

        Order::calculateTotals($order->id);
        redirect('cart');
    }

    public function update($itemId, $productId)
    {
        $quantity = (int)$_POST['quantity'];

        if ($quantity < 1) {
            $quantity = 1;
        }

        $item = OrderItem::where('id', $itemId)->where('product_id', $productId)->first();

        if ($item) {
            if ($item->quantity != $quantity)
                flash('message', 'تعداد با موفقیت ویرایش شد.');
            $item->quantity = $quantity;
            $item->save();
        }

        Order::calculateTotals($item->order_id);
        redirect('cart');
    }

    public function delete($itemId)
    {
        $item = OrderItem::find($itemId);
        $order_id = $item->order_id;
        if ($item) {
            $item->delete();
        }
        flash('message', 'محصول با موفقیت حذف شد.', 'alert alert-danger');

        Order::calculateTotals($order_id);
        return redirect('cart');
    }
}