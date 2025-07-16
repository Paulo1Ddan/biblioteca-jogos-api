<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    /** @use HasFactory<\Database\Factories\GameFactory> */
    use HasFactory;

    protected $fillable = ["title", "publisher", "release_date", "platforms"];
    protected $hidden = ['pivot'];

    public function users()
    {
        return $this->belongsToMany(User::class, "games_library", "game_id", "user_id")->withTimestamps();
    }
}
