<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sticker extends Model
{
    use HasFactory;

 /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['title'];


    public function image()
    {
        return $this->hasOne(Image::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
