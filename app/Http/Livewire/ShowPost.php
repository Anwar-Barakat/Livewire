<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;

class ShowPost extends Component
{
    public $title;
    public $slug;
    public $body;
    public $image;

    public function mount($slug)
    {
        $this->retrieve_post($slug);
    }

    public function retrieve_post($slug)
    {
        $post = Post::where('slug', $slug)->first();
        $this->title    = $post->title;
        $this->body     = $post->body;
        $this->image    = $post->image;
    }

    public function render()
    {
        return view('livewire.show-post');
    }
}