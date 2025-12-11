<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = ['name', 'slug', 'usage_count'];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public static function findOrCreateFromString(string $tagString): self
    {
        $slug = Str::slug($tagString);

        $tag = static::firstOrCreate(
            ['slug' => $slug],
            ['name' => $tagString]
        );

        return $tag;
    }

    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    public function decrementUsage(): void
    {
        $this->decrement('usage_count');
    }
}
