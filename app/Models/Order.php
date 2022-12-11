<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function bond(): BelongsTo
    {
        return $this->belongsTo(Bond::class, 'bond_id', 'id');
    }

}
