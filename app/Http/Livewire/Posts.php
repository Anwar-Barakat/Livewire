<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Posts extends Component
{
    use WithPagination, WithFileUploads, LivewireAlert;

    public $title;
    public $slug_url;
    public $body;
    public $image;
    public $post_image;
    public $post_image_name;
    public $modalFormVisible = false;

    public function all_posts()
    {
        return Post::orderBy('id', 'desc')->paginate(8);
    }

    public function render()
    {
        return view('livewire.posts', [
            'posts' => $this->all_posts()
        ]);
    }

    public function showCreateModal()
    {
        $this->modalFormVisible = true;
    }

    public function updatedTitle($value)
    {
        $this->slug_url = Str::slug($value);
    }

    public function rules()
    {
        return [
            'title'         => ['required'],
            'slug_url'      => ['required', Rule::unique('posts', 'slug')],
            'body'          => ['required'],
            'post_image'    => ['required', 'image', 'max:1024'],
        ];
    }

    public function model_data()
    {
        return [
            'title'         => $this->title,
            'body'          => $this->body,
            'image'         => $this->post_image_name,
        ];
    }

    public function reset_data()
    {
        $this->title        = null;
        $this->slug         = null;
        $this->body         = null;
        $this->image         = null;
        $this->post_image_name   = null;
    }

    public function store()
    {
        $this->validate();
        if ($this->post_image != '') {
            $this->post_image_name = md5($this->post_image . microtime()) . '.' . $this->post_image->extension();
            $this->post_image->storeAs('/', $this->post_image_name, 'uploads');
        }
        auth()->user()->posts()->create($this->model_data());
        $this->reset_data();
        $this->modalFormVisible = false;

        $this->alert('success', 'post added successfully', [
            'position'          => 'center',
            'timer'             => 3000,
            'toast'             => true,
            'text'              => null,
            'showConfirmButton' => false,
            'showCancelButton'  => false,
        ]);
    }
}
