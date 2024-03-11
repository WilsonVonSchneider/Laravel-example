<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-400 leading-tight">
            {{ __('Edit Comment') }}
        </h2>
    </x-slot>
    <x-flash-message />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('comments.update', $comment->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mt-4">
                            <x-input-label for="commentText" :value="__('Comment Body')" />
                            <x-text-input id="commentText" class="block mt-1 w-full" type="text" name="commentText" value="{{ $comment->commentText }}" required autofocus />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Save Changes') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

