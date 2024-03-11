<x-app-layout>
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-100 rounded-lg shadow-lg p-6 mb-6">
            <h2 class="mb-5 text-xl font-bold">{{ $user->name }}</h2>
            <p class="mb-5">email: {{ $user->email}}</p>
            <p class="mb-5">joined: {{ $user->created_at}}</p>
            <p class="mb-5">category: {{ $user->category}}</p>
            <p class="mb-5">language: {{ $user->language}}</p>
            <p class="mb-5">country: {{ $user->country}}</p>
            <div class="flex items-center space-x-2">
                <a href="/admin/users/{{$user->id}}/favourites" class="text-sm text-gray-500 hover:underline">Favourites</a>
                <a href="/admin/users/{{$user->id}}/comments" class="text-sm text-gray-500 hover:underline">Comments</a>
                <a href="/admin/users/{{$user->id}}/logs" class="text-sm text-gray-500 hover:underline">Logs</a>
            </div>
        </div>
    </div>
</x-app-layout>
