<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','total','status','payment_id'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
