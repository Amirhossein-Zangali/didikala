<?php

namespace didikala\models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    public $timestamps = false;
    protected $primaryKey = 'id';

    // ارتباط با سفارش
    public function order() {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    // ارتباط با محصول
    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

}