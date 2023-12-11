<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentWithdrawal extends Model
{
    use HasFactory;

    protected $appends = ['method_details'];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function getMethodDetailsAttribute()
    {
        return json_decode($this->withdrawal_method_details);
    }
}
