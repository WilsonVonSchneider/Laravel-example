<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash-message />

                {{-- Favourites --}}
                <div class="bg-gray-100 rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold mb-4">Favourites</h2>
                    @if (count($favourites) > 0)
                        @foreach ($favourites as $index => $favorite)
                            <div class="mb-6">
                                @if($favorite->imageUrl)
                                    <img width="1600" height="500" src="{{ $favorite->imageUrl }}">
                                @else
                                    {{-- <img width="100" height="200" src="{{asset('pictures/no_image_r.jpeg')}}"> --}}
                                @endif
                                <a href="{{ $favorite->url }}"
                                    class="text-xl font-bold text-blue-500 hover:underline">{{ $favorite->title ?? 'No title' }}</a>
                                <p class="text-gray-600">{{ $favorite->description ?? 'No description' }}</p>
                                <p class="text-gray-700">By {{ $favorite->author ?? 'Unknown author' }}</p>

                                <form method="POST" action="/favourites/{{$favorite->id}}">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="flex items-center justify-center mt-4 px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:underline">
                                        <i class="fa fa-remove text-red-500"> &#160</i> Remove from favorites
                                    </button>
                                </form>

                                <div class="mt-5">
                                    <div class="mt-5">
                                        <form class="mb-4 flex flex-row" method="POST" action="{{ route('comments.store') }}">
                                            @csrf
                                            <div class="flex flex-col mr-2 w-full">
                                                <textarea name="commentText" id="commentText" rows="1" placeholder="Leave your comment here"
                                                          class="rounded-lg border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                                            </div>
                                            <input type="hidden" id="userId" name="userId" value={{ auth()->user()->id }}>
                                            <input type="hidden" id="url" name="url" value="{{ $favorite->url }}">
                                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Submit</button>
                                        </form>
                                    </div>

                                    <!-- Comment section body -->
                                    <div class="mt-4 max-h-0 overflow-hidden transition-all duration-500 ease-in-out"
                                        id="comments-section-{{ $index }}">
                                        <!-- Comment 1 -->
                                        @php
                                            $commentCount = 0;
                                        @endphp
                                        @foreach($comments as $comment)
                                        @if ($comment->url == $favorite->url)
                                        @if ($comment->userId == auth()->user()->id)
                                        <div class="bg-blue-500 rounded-lg shadow-md p-4 mb-4 flex">
                                            <div>
                                                <h4 class="text-lg font-medium text-white">{{$comment->user->name}}</h4>
                                                <p class="text-white">{{$comment->commentText}}</p>
                                            </div>
                                            <div class="ml-auto flex">
                                                <form method="GET" action="comments/{{$comment->id}}/edit">
                                                    <button class="flex items-center justify-center mt-4 px-4 py-2 text-gray-600 rounded-lg mr-2">
                                                        <i class="fa fa-edit text-white"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="comments/{{$comment->id}}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="flex items-center justify-center mt-4 px-4 py-2 text-gray-600 rounded-lg">
                                                        <i class="fa fa-remove text-white"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        @else
                                        <div class="bg-gray-100 rounded-lg shadow-md p-4 mb-4">
                                            <h4 class="text-lg font-medium">{{$comment->user->name}}</h4>
                                            <p class="text-gray-600">{{$comment->commentText}}</p>
                                        </div>
                                        @endif
                                        @php
                                            $commentCount++;
                                        @endphp
                                        @endif
                                        @endforeach
                                    </div>
                                    <h3 class="text-lg font-bold mb-5 text-gray-600"><button class="text-gray-600 hover:text-gray-800"
                                        id="expand-comments-btn-{{ $index }}">
                                        <i class="mr-2"></i>
                                        <span>Comments:</span>
                                    </button> {{$commentCount}}</h3>


                                </div>
                                <script>
                                    const expandBtn{{ $index }} = document.querySelector('#expand-comments-btn-{{ $index }}');
                                    const commentsSection{{ $index }} = document.querySelector('#comments-section-{{ $index }}');

                                    let isExpanded{{ $index }} = false;

                                    expandBtn{{ $index }}.addEventListener('click', () => {
                                        if (!isExpanded{{ $index }}) {
                                            commentsSection{{ $index }}.style.maxHeight =
                                                `${commentsSection{{ $index }}.scrollHeight}px`;
                                            expandBtn{{ $index }}.innerHTML =
                                                `<i class="mr-2"></i><span>Comments:</span>`;
                                        } else {
                                            commentsSection{{ $index }}.style.maxHeight = `0px`;
                                            expandBtn{{ $index }}.innerHTML =
                                                `<i class="mr-2"></i><span>Comments:</span>`;
                                        }
                                        isExpanded{{ $index }} = !isExpanded{{ $index }};
                                    });
                                </script>

                        @endforeach
                    <div class="mt-20">
                        {{ $favourites->appends(['perPage' => $perPage])->links() }}

                    </div>
                    @else
                        <p>No favourites found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>



</x-app-layout>
