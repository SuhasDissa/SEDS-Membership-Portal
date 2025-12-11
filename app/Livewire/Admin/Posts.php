<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;

class Posts extends Component
{
    use WithFileUploads;

    public $search = '';
    public $statusFilter = 'all';
    public $categoryFilter = 'all';

    // Form fields
    public $showModal = false;
    public $editingPostId = null;
    public $title = '';
    public $content = '';
    public $category = 'general';
    public $status = 'published';
    public $is_featured = false;
    public $image = null; // Uploaded image file
    public $image_url = ''; // Existing image URL
    public $tags = ''; // Comma-separated tags

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'status' => 'required|in:draft,published',
            'is_featured' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120', // 5MB max
            'image_url' => 'nullable|url',
            'tags' => 'nullable|string|max:500',
        ];
    }

    public function getPostsProperty()
    {
        $query = Post::with('user');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('content', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->categoryFilter !== 'all') {
            $query->where('category', $this->categoryFilter);
        }

        return $query->latest()->get();
    }

    public function getStatsProperty()
    {
        return [
            'total' => Post::count(),
            'published' => Post::where('status', 'published')->count(),
            'draft' => Post::where('status', 'draft')->count(),
            'featured' => Post::where('is_featured', true)->count(),
        ];
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal($postId)
    {
        $post = Post::findOrFail($postId);

        $this->editingPostId = $post->id;
        $this->title = $post->title;
        $this->content = $post->content;
        $this->category = $post->category;
        $this->status = $post->status;
        $this->is_featured = $post->is_featured;
        $this->image_url = $post->image_url ?? '';
        $this->tags = $post->tags->pluck('name')->implode(', '); // Load tags

        $this->showModal = true;
    }

    public function savePost()
    {
        $this->validate();

        // Handle image upload
        $imagePath = $this->image_url; // Keep existing URL if no new upload
        if ($this->image) {
            $imagePath = $this->image->store('post_images', 'public');
            $imagePath = 'storage/' . $imagePath; // Add storage prefix for URL
        }

        $data = [
            'title' => $this->title,
            'content' => $this->content,
            'category' => $this->category,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'image_url' => $imagePath ?: null,
        ];

        if ($this->editingPostId) {
            $post = Post::findOrFail($this->editingPostId);
            $post->update($data);
            $post->syncTagsFromString($this->tags); // Sync tags
            session()->flash('success', 'Post updated successfully!');
        } else {
            $data['user_id'] = auth()->id();
            $post = Post::create($data);
            $post->syncTagsFromString($this->tags); // Sync tags
            session()->flash('success', 'Post created successfully!');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function deletePost($postId)
    {
        $post = Post::findOrFail($postId);
        $title = $post->title;
        $post->delete();

        session()->flash('success', "Post '{$title}' has been deleted.");
    }

    public function toggleFeatured($postId)
    {
        $post = Post::findOrFail($postId);
        $post->update(['is_featured' => !$post->is_featured]);

        $status = $post->is_featured ? 'featured' : 'unfeatured';
        session()->flash('success', "Post has been {$status}!");
    }

    private function resetForm()
    {
        $this->editingPostId = null;
        $this->title = '';
        $this->content = '';
        $this->category = 'general';
        $this->status = 'published';
        $this->is_featured = false;
        $this->image = null;
        $this->image_url = '';
        $this->tags = '';
    }

    public function render()
    {
        return view('livewire.admin.posts');
    }
}
