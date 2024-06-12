<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'number_of_details',
        'number_of_devices',
        'J_parameter',
        'T_l_w',
        'A_w_i',
    ];

    public function details()
    {
        return $this->belongsToMany(Detail::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
