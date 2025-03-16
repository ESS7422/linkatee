<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class links extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'link',
        'order',
        'icon',
        'icon_image',
        'background_color',
        'text_color',
        'is_active'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_links');
    }
    public function link()
    {
        return $this->belongsTo(Links::class);
    }
}
