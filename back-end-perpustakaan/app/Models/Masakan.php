<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masakan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $primaryKey = 'id';
    protected $fillable = [
        'name', 'description', 'image', 'price'
    ];

    public function detail()
    {
        return $this->hasOne(MasakanDetail::class, 'masakan_id', 'id');
    }

    public function bahans()
    {
        return $this->hasMany(Bahan::class, 'masakan_id');
    }

    public function kategoris()
    {
        return $this->belongsToMany(Kategori::class);
    }

    public function scopeFilter($query, array $filters) {
        // dd($filters['request']['gender']);
        $query->when($filters['request']['name'] ?? false, function ($query, $value) {
            return $query->where('name', 'like', "%{$value}%");
        });
    }
}
