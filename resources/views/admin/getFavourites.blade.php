<x-app-layout>
    <div class="py-12">
        <x-flash-message />
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="mb-5 text-xl font-bold"><a class="hover:underline" href="/admin/users/{{$user->id}}">{{ $user->name }}</a> favourites:</h2>
            @if(count($favourites))
            <ul class="divide-y divide-gray-200">
                @foreach($favourites as $favourite)
                    <li class="py-4">
                        <div class="flex items-center justify-between space-x-4">
                            <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">
                                        <a class="hover:underline" href="{{$favourite->zrl}}">{{ $favourite->title }}</a>
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $favourite->description }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <form method="POST" action="/favourites/{{$favourite->id}}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="flex items-center justify-center mt-4 px-4 py-2 text-gray-600 rounded-lg">
                                        <i class="fa fa-remove text-red-500"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
            @else
            <p>No favourites found</p>
            @endif
            <div class="mt-20">
                {{ $favourites->appends(['perPage' => $perPage])->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
