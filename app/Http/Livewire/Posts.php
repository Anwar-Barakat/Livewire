<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Illuminate\Support\Facades\File;
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
    public $modalId;
    public $modalFormVisible = false;
    public $confirmPostDelete = false;
    public $showModalDelete = false;

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
        $this->reset_data();
        $this->modalFormVisible = true;
    }

    public function showUpdateModal($id)
    {
        $this->modalFormVisible = true;
        $this->reset_data();
        $this->modalId = $id;
        $this->get_data_post();
    }

    public function showModalDelete($id)
    {
        $this->confirmPostDelete = true;
        $this->modalId = $id;
    }

    public function updatedTitle($value)
    {
        $this->slug_url = Str::slug($value);
    }

    public function rules()
    {
        return [
            'title'         => ['required'],
            'slug_url'      => ['required', Rule::unique('posts', 'slug')->ignore($this->modalId)],
            'body'          => ['required'],
            'post_image'    => [Rule::requiredIf(!$this->modalId), 'max:1024'],
        ];
    }

    public function model_data()
    {
        $data =  [
            'title'         => $this->title,
            'body'          => $this->body,
        ];
        if ($this->post_image != '') {
            $data['image'] =  $this->post_image_name;
        }
        return $data;
    }

    public function get_data_post()
    {
        $data           = Post::find($this->modalId);
        $this->title    = $data->title;
        $this->slug_url = $data->slug;
        $this->body     = $data->body;
        $this->image    = $data->image;
    }

    public function reset_data()
    {
        $this->title            = null;
        $this->slug             = null;
        $this->body             = null;
        $this->image            = null;
        $this->post_image       = null;
        $this->post_image_name  = null;
        $this->modalId          = null;
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

    public function update()
    {
        $this->validate();
        $post = Post::where('id', $this->modalId)->first();
        if ($this->post_image != '') {
            if ($post->image != '') {
                if (File::exists('images/' . $post->image)) {
                    unlink('images/' . $post->image);
                }
            }
            $this->post_image_name = md5($this->post_image . microtime()) . '.' . $this->post_image->extension();
            $this->post_image->storeAs('/', $this->post_image_name, 'uploads');
        }
        $post->update($this->model_data());
        $this->modalFormVisible = False;
        $this->reset_data();
        $this->alert('success', 'post updated successfully', [
            'position'          => 'center',
            'timer'             => 3000,
            'toast'             => true,
            'text'              => null,
            'showConfirmButton' => false,
            'showCancelButton'  => false,
        ]);
    }

    public function destroy()
    {
        $post = Post::where('id', $this->modalId)->first();
        if ($post->image != '') {
            if (File::exists('images/' . $post->image)) {
                unlink('images/' . $post->image);
            }
        }
        $post->delete();
        $this->confirmPostDelete = false;
        $this->resetPage();
        $this->alert('success', 'post deleted successfully', [
            'position'          => 'center',
            'timer'             => 3000,
            'toast'             => true,
            'text'              => null,
            'showConfirmButton' => false,
            'showCancelButton'  => false,
        ]);
    }
}