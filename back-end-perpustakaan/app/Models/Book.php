<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $guard = [
        'id'
    ];

    protected $fillable = [
        'isbn',
        'title',
        'cover',
        'year',
        'publisher_id',
        'author_id',
        'category_id',
        'qty',
        'price'
    ];

    public function publisher() {
        
        return $this->belongsTo('App\Models\Publisher', 'publisher_id');
    }

    public function category() {
        
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function author() {
        
        return $this->belongsTo('App\Models\Author', 'author_id');
    }

    public function scopeFilter($query, array $filters) {
        $query->when($filters['request']['search'] ?? false, function ($query, $value) {
            return $query->where('title','like', '%'. $value .'%')->orWhere('isbn','like', '%'. $value .'%');
        });

        $query->when($filters['request']['qty'] ?? false, function ($query, $value) {
            return $query->where('qty','>', 0);
        });
    }
}