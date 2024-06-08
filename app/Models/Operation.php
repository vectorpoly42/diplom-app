<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    protected $table = 'operations';

    protected $fillable = [
        'detail_id', 'type', 'time',
    ];

    public function detail()
    {
        return $this->belongsTo(Detail::class, 'detail_id');
    }
}
