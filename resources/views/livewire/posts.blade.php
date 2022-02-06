@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <style>
        textarea {
            border: none
        }

    </style>
@endpush
<div>
    <div class="flex items-center py-3 justify-end text-right">
        <x-jet-button wire:click="showCreateModal">
            {{ __('Create Post') }}
        </x-jet-button>
    </div>
    <table class="w-full divide-y divide-gray-200">
        <thead>
            <tr>
                <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-blue-500 tracking-wider">Id</th>
                <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-blue-500 tracking-wider ">Image</th>
                <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-blue-500 tracking-wider">Title</th>
                <th class="px-6 py-3 border-b-2 border-gray-200 text-blue-500 tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($posts as $post)
                <tr>
                    <td class="px-6 py-3 border-b-2 border-gray-200 text-left">{{ $post->id }}</td>
                    <td class="px-6 py-3 border-b-2 border-gray-200 text-left"><img
                            src="{{ asset('images/' . $post->image) }}" alt="{{ $post->title }}" width="60"></td>
                    <td class="px-6 py-3 border-b-2 border-gray-200 text-left">{{ $post->title }}</td>
                    <td class="px-6 py-3 border-b-2 border-gray-200 ">
                        <div class="flex items-center py-3 justify-end text-right">
                            <x-jet-button class="mr-3" wire:click="showUpdateModal({{ $post->id }})">
                                {{ __('edit') }}
                            </x-jet-button>
                            <x-jet-danger-button wire:click="showModalDelete({{ $post->id }})">
                                {{ __('delete') }}
                            </x-jet-danger-button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="px-6 py-3 border-b-2 border-gray-200 text-left" colspan="4">No Posts Yet</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="py-4">
        {{ $posts->links() }}
    </div>

    <x-jet-dialog-modal wire:model="modalFormVisible">
        <x-slot name="title">
            {{ $modalId ? __('Edit Post') : __('Create Post') }}
        </x-slot>
        <x-slot name="content">
            <div class="mt-5">
                <x-jet-label for="title" value="{{ __('Title') }}"></x-jet-label>
                <x-jet-input type="text" id="title" wire:model.debounce.500ms="title" class="py-2 block w-full mt-2">
                </x-jet-input>
                @error('title')
                    <span class="text-red-300 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-5">
                <x-jet-label for="slug" value="{{ __('Slug') }}"></x-jet-label>
                <div class="ml-1 flex rounded-md shadow-sm">
                    <span
                        class="py-2 inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                        {{ config('app.url') . '/' }}
                    </span>
                    <input type="text" id="slug" wire:model="slug_url"
                        class="border-gray-300 block w-full flex-1 form-input rounded-none rounded-r-md transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                        placeholder="Slug Url">
                </div>
                @error('slug_url')
                    <span class="text-red-300 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="row mt-5 w-full">
                <div class="box border rounded flex flex-col shadow bg-white">
                    <div class="box__title bg-grey-lighter px-3 py-2 border-b">
                        <h3 class="text-sm text-grey-darker font-medium">
                            <x-jet-label for="body" value="{{ __('Body') }}"></x-jet-label>
                        </h3>
                    </div>
                    <textarea id="body"
                        class="text-grey-darkest flex-1 p-2 m-1 bg-transparent focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                        wire:model="body" placeholder="message..."></textarea>
                </div>
                @error('body')
                    <span class="text-red-300 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3 w-full mt-5">
                <x-jet-label for="image" class="form-label inline-block mb-2 text-gray-700"
                    value="{{ __('Image') }}"></x-jet-label>
                @if ($post_image)
                    <div class="py-3">
                        <div class="mt-2 flex rounded-md">
                            <span
                                class="inline-flex items-center p-3 rounded border border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                <img src="{{ $post_image->temporaryUrl() }}" alt="" width="200">
                            </span>
                        </div>
                    </div>
                @endif
                @if ($image)
                    <div class="py-3">
                        <div class="mt-2 flex rounded-md">
                            <span
                                class="inline-flex items-center p-3 rounded border border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                <img src="{{ asset('images/' . $image) }}" alt="" width="200">
                            </span>
                        </div>
                    </div>
                @endif
                <input
                    class="form-control
                                block
                                w-full
                                px-3
                                py-2
                                text-base
                                font-normal
                                text-gray-700
                                bg-white bg-clip-padding
                                border border-solid border-gray-300
                                rounded
                                transition
                                ease-in-out
                                m-0
                                focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                    type="file" id="image" wire:model="post_image">
                @error('post_image')
                    <span class="text-red-300 text-sm">{{ $message }}</span>
                @enderror
            </div>

        </x-slot>
        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$toggle('modalFormVisible')">
                {{ __('cancel') }}</x-jet-secondary-button>
            @if ($modalId)
                <x-jet-button wire:click="update">{{ __('update') }}</x-jet-button>
            @else
                <x-jet-button wire:click="store">{{ __('save') }}</x-jet-button>
            @endif
        </x-slot>

    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="confirmPostDelete">
        <x-slot name="title">
            {{ __('Delete Post') }}
        </x-slot>
        <x-slot name="content">
            {{ __('Are You Sure The Deletion Process ??') }}
        </x-slot>
        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$toggle('confirmPostDelete')">
                {{ __('cancel') }}</x-jet-secondary-button>
            <x-jet-danger-button wire:click="destroy">{{ __('delete') }}</x-jet-danger-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
