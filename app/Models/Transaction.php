<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['subscription_id','qty','total'];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}