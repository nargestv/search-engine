<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Book extends Model
{
    use HasFactory,Sluggable;

    protected $fillable = ['title', 'content','image','slug'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function authors()
    {
       return $this->belongsTo(Author::class);
    }

    public function publishers()
    {
       return $this->belongsTo(Publisher::class);
    }
 
}
