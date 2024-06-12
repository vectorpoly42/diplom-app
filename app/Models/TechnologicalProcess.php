<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnologicalProcess extends Model
{
    use HasFactory;

    protected $table = 'technological_processes';

    protected $fillable = [
        'detail_id',
        'operations',
    ];

    public function detail()
    {
        return $this->belongsTo(Detail::class);
    }
}
