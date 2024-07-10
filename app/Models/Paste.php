<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paste extends Model
{

    use HasFactory;
    public $guarded = [];

    public function user()
    {
        return $this->belongsTo((Paste::class));
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'paste_tags');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
