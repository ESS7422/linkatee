<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class user_links extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'user_id',
        'link_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    

}


