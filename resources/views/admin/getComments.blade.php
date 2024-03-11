<x-app-layout>
    <div class="py-12">
        <x-flash-message />
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="mb-5 text-xl font-bold"><a class="hover:underline" href="/admin/users/{{$user->id}}">{{ $user->name }}</a> comments:</h2>
            @if (count($comments))
                <ul class="divide-y divide-gray-200">
                    @foreach ($comments as $comment)
                        <li class="py-4">
                            <div class="flex items-center justify-between space-x-4">
                                <div class="ml-4">
                                    <p class="text-sm text-gray-500">{{ $comment->commentText }}</p>
                                </div>
                                <div class="ml-auto flex">
                                    <form method="POST" action="/comments/{{ $comment->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            class="flex items-center justify-center mt-4 px-4 py-2 text-gray-600 rounded-lg">
                                            <i class="fa fa-remove text-red-500"></i>
                                        </button>
                                    </form>
                                    <form method="GET" action="/comments/{{ $comment->id }}/edit">
                                        <button
                                            class="flex items-center justify-center mt-4 px-4 py-2 text-gray-600 rounded-lg mr-2">
                                            <i class="fa fa-edit text-gray-500"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No comments found</p>
            @endif
            <div class="mt-20">
                {{ $comments->appends(['perPage' => $perPage])->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
