<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'overtime',
        'date',
        'from',
        'untill',
        'user_id'
        
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
