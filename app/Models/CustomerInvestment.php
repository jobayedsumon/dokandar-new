<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerInvestment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['customer', 'package'];

    protected $appends = ['profit_earned'];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function package()
    {
        return $this->belongsTo(InvestmentPackage::class, 'investment_id');
    }

    public function getProfitEarnedAttribute()
    {
        $until = Carbon::parse($this->redeemed_at) ?? now();
        $days  = $until->diffInDays($this->created_at);

        return $this->package->daily_profit * $days;
    }
}
