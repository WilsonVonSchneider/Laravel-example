<x-app-layout>
    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form class="flex items-center justify-center mb-8 w-full" action="/admin/users">
                <div class="relative mr-4 w-full">
                    <i class="absolute top-3 left-3 text-gray-400 hover:text-gray-500 fa fa-search"></i>
                    <input type="text" name="search" placeholder="Search users..."
                        class="w-full h-12 pl-10 pr-16 rounded-lg focus:outline-none focus:shadow text-gray-500">
                </div>
                <button type="submit"
                    class="h-12 px-6 text-white rounded-lg bg-red-500  hover:bg-red-400 ">Search</button>
            </form>
            <h2 class="mb-5 text-xl font-bold">USERS:</h2>
            <ul class="divide-y divide-gray-200">
                @foreach($users as $user)
                    <li class="py-4">
                        <div class="flex items-center justify-between space-x-4">
                            <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">
                                        <a class="hover:underline" href="/admin/users/{{$user->id}}">{{ $user->name }}</a>
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="/admin/users/{{$user->id}}/favourites" class="text-sm text-gray-500 hover:underline">Favourites</a>
                                <a href="/admin/users/{{$user->id}}/comments" class="text-sm text-gray-500 hover:underline">Comments</a>
                                <a href="/admin/users/{{$user->id}}/logs" class="text-sm text-gray-500 hover:underline">Logs</a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="mt-20">
                {{ $users->appends(['perPage' => $perPage])->links() }}
            </div>
        </div>
    </div>
</x-app-layout>


