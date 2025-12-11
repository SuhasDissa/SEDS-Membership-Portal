<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'category',
        'status',
        'is_featured',
        'image_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
        ];
    }

    /**
     * User relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for published posts only
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for featured posts
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get category label
     */
    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'announcement' => '📢 Announcement',
            'event' => '📅 Event',
            'news' => '📰 News',
            'achievement' => '🎉 Achievement',
            'resource' => '📚 Resource',
            default => 'ℹ️ General',
        };
    }

    /**
     * Tags relationship
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    /**
     * Sync tags from comma-separated string
     */
    public function syncTagsFromString(?string $tagString): void
    {
        if (empty($tagString)) {
            $this->tags()->detach();
            return;
        }

        $tagNames = array_map('trim', explode(',', $tagString));
        $tagIds = [];

        foreach ($tagNames as $tagName) {
            if (!empty($tagName)) {
                $tag = Tag::findOrCreateFromString($tagName);
                $tagIds[] = $tag->id;
            }
        }

        $this->tags()->sync($tagIds);
    }
}
