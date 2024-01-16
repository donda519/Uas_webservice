<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function books() {
        
        return $this->hasMany('App\Models\Book', 'category_id');
    }

    public function scopeFilter($query, array $filters) {
        $query->when($filters['request']['search'] ?? false, function ($query, $value) {
            return $query->where('name','like', '%'. $value .'%');
        });
    }

}
