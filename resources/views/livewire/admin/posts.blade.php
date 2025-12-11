<div class="p-6">
    {{-- Header --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-base-content">Manage Posts</h1>
            <p class="text-base-content/70">Create and manage feed posts for members</p>
        </div>
        <button wire:click="openCreateModal" class="btn btn-primary">
            <x-icon name="o-plus-circle" class="w-5 h-5" />
            Create Post
        </button>
    </div>

    {{--Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-stat title="Total Posts" :value="$this->stats['total']" icon="o-document-text"
            class="bg-primary text-primary-content shadow-sm hover:shadow-md transition-shadow" />
        <x-stat title="Published" :value="$this->stats['published']" icon="o-check-circle"
            class="bg-success text-success-content shadow-sm hover:shadow-md transition-shadow" />
        <x-stat title="Drafts" :value="$this->stats['draft']" icon="o-document"
            class="bg-warning text-warning-content shadow-sm hover:shadow-md transition-shadow" />
        <x-stat title="Featured" :value="$this->stats['featured']" icon="o-star"
            class="bg-accent text-accent-content shadow-sm hover:shadow-md transition-shadow" />
    </div>

    {{-- Filters --}}
    <div class="card bg-base-100 shadow-sm mb-6">
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-input label="Search" wire:model.live="search" placeholder="Search posts..." icon="o-magnifying-glass"
                    inline />
                <x-select label="Status" wire:model.live="statusFilter" :options="[
        ['id' => 'all', 'name' => 'All Status'],
        ['id' => 'published', 'name' => 'Published'],
        ['id' => 'draft', 'name' => 'Draft'],
    ]"
                    icon="o-funnel" inline />
                <x-select label="Category" wire:model.live="categoryFilter" :options="[
        ['id' => 'all', 'name' => 'All Categories'],
        ['id' => 'announcement', 'name' => '📢 Announcement'],
        ['id' => 'event', 'name' => '📅 Event'],
        ['id' => 'news', 'name' => '📰 News'],
        ['id' => 'achievement', 'name' => '🎉 Achievement'],
        ['id' => 'resource', 'name' => '📚 Resource'],
        ['id' => 'general', 'name' => 'ℹ️ General'],
    ]"
                    icon="o-tag" inline />
            </div>
        </div>
    </div>

    {{-- Posts List --}}
    <div class="space-y-4">
        @if($this->posts->count() > 0)
            @foreach($this->posts as $post)
                <div wire:key="post-{{ $post->id }}" class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="card-body">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <h3 class="card-title">{{ $post->title }}</h3>
                                    @if($post->is_featured)
                                        <span class="badge badge-accent gap-1">
                                            <x-icon name="o-star" class="w-3 h-3" />
                                            Featured
                                        </span>
                                    @endif
                                    <span class="badge badge-ghost">{{ $post->category_label }}</span>
                                    @if($post->status === 'draft')
                                        <span class="badge badge-warning">Draft</span>
                                    @else
                                        <span class="badge badge-success">Published</span>
                                    @endif
                                </div>
                                <p class="text-base-content/70">{{ Str::limit($post->content, 150) }}</p>
                                <div class="text-xs text-base-content/60 mt-2">
                                    By {{ $post->user->name }} • {{ $post->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button wire:click="toggleFeatured({{ $post->id }})"
                                    class="btn btn-sm {{ $post->is_featured ? 'btn-accent' : 'btn-ghost' }}"
                                    title="{{ $post->is_featured ? 'Unfeature' : 'Feature' }}">
                                    <x-icon name="o-star" class="w-4 h-4" />
                                </button>
                                <button wire:click="openEditModal({{ $post->id }})" class="btn btn-ghost btn-sm" title="Edit">
                                    <x-icon name="o-pencil" class="w-4 h-4" />
                                </button>
                                <button wire:click="deletePost({{ $post->id }})"
                                    wire:confirm="Are you sure you want to delete this post?"
                                    class="btn btn-ghost btn-sm text-error" title="Delete">
                                    <x-icon name="o-trash" class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body text-center py-12">
                    <x-icon name="o-inbox" class="w-16 h-16 mx-auto text-base-content/30 mb-4" />
                    <p class="text-base-content/70">No posts found</p>
                    <button wire:click="openCreateModal" class="btn btn-primary mt-4">
                        <x-icon name="o-plus-circle" class="w-5 h-5" />
                        Create Your First Post
                    </button>
                </div>
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if($showModal)
        <div class="modal modal-open">
            <div class="modal-box max-w-3xl">
                <h3 class="font-bold text-lg mb-4">{{ $editingPostId ? 'Edit Post' : 'Create New Post' }}</h3>

                <div class="space-y-4">
                    <x-input label="Title" wire:model="title" placeholder="Enter post title..." />

                    <x-textarea label="Content" wire:model="content" placeholder="Write your post content..." rows="8"
                        hint="Markdown supported" />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-select label="Category" wire:model="category" :options="[
                ['id' => 'announcement', 'name' => '📢 Announcement'],
                ['id' => 'event', 'name' => '📅 Event'],
                ['id' => 'news', 'name' => '📰 News'],
                ['id' => 'achievement', 'name' => '🎉 Achievement'],
                ['id' => 'resource', 'name' => '📚 Resource'],
                ['id' => 'general', 'name' => 'ℹ️ General'],
            ]" />

                        <x-select label="Status" wire:model="status" :options="[
                ['id' => 'draft', 'name' => 'Draft'],
                ['id' => 'published', 'name' => 'Published'],
            ]" />
                    </div>

                    {{-- Image Upload --}}
                    <div class="form-control" x-data="{ imagePreview: null }">
                        <label class="label">
                            <span class="label-text">Post Image (Optional)</span>
                        </label>
                        <x-file 
                            wire:model="image" 
                            accept="image/*" 
                            hint="Max size: 5MB. Supports JPEG, PNG, WebP"
                            x-on:change="
                                const file = $event.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = (e) => imagePreview = e.target.result;
                                    reader.readAsDataURL(file);
                                }
                            "
                        >
                            <div wire:loading wire:target="image" class="flex items-center justify-center h-40 bg-base-200 rounded-lg">
                                <div class="text-center">
                                    <span class="loading loading-spinner loading-lg text-primary"></span>
                                    <p class="text-sm text-base-content/50 mt-2">Uploading image...</p>
                                </div>
                            </div>
                            
                            <div wire:loading.remove wire:target="image">
                                <template x-if="imagePreview">
                                    <img :src="imagePreview" class="h-40 rounded-lg" alt="Preview" />
                                </template>
                                
                                <template x-if="!imagePreview">
                                    @if($image)
                                        <img src="{{ $image->temporaryUrl() }}" class="h-40 rounded-lg" alt="Preview" />
                                    @elseif($image_url)
                                        <img src="{{ asset($image_url) }}" class="h-40 rounded-lg" alt="Current Image" />
                                    @else
                                        <div class="flex items-center justify-center h-40 bg-base-200 rounded-lg">
                                            <div class="text-center">
                                                <x-icon name="o-photo" class="w-12 h-12 mx-auto text-base-content/30 mb-2" />
                                                <p class="text-sm text-base-content/50">Click to upload image</p>
                                            </div>
                                        </div>
                                    @endif
                                </template>
                            </div>
                        </x-file>
                    </div>

                    <x-input label="Tags (Optional)" wire:model="tags" placeholder="space, technology, research"
                        hint="Separate tags with commas. Tags help organize and categorize posts." />

                    <x-checkbox label="Featured Post" wire:model="is_featured"
                        hint="Featured posts appear at the top of the feed" />
                </div>

                <div class="modal-action">
                    <button wire:click="savePost" class="btn btn-primary">
                        <x-icon name="o-check" class="w-5 h-5" />
                        {{ $editingPostId ? 'Update' : 'Create' }}
                    </button>
                    <button wire:click="$set('showModal', false)" class="btn btn-ghost">Cancel</button>
                </div>
            </div>
            <div class="modal-backdrop" wire:click="$set('showModal', false)"></div>
        </div>
    @endif
</div>