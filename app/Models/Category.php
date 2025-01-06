<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    // Define the attributes that are mass assignable
    protected $fillable = [
        'category_image',
        'category_name',
        'slug'
    ];
    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->slug = Str::slug($model->category_name);
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
