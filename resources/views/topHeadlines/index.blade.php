<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash-message />

            {{-- Search bar --}}
            <form class="flex items-center justify-center mb-8 w-full" action="/topHeadlines">
                <div class="relative mr-4 w-full">
                    <i class="absolute top-3 left-3 text-gray-400 hover:text-gray-500 fa fa-search"></i>
                    <input type="text" name="q" placeholder="Search top headlines..."
                        class="w-full h-12 pl-10 pr-16 rounded-lg focus:outline-none focus:shadow text-gray-500">
                    @if ($source)
                        <input type="hidden" id="source" name="source" value="{{ $source }}">
                    @else
                        @if ($category)
                            <input type="hidden" id="category" name="category" value="{{ $category }}">
                        @else
                            <input type="hidden" id="category" name="category" value="{{ auth()->user()->category }}">
                        @endif
                    @endif
                </div>
                <button type="submit"
                    class="h-12 px-6 text-white rounded-lg bg-red-500  hover:bg-red-400 ">Search</button>
            </form>

            {{-- Categories --}}
            <div class="bg-gray-100 rounded-lg mb-6 shadow-md p-2">
                <div class="flex flex-wrap justify-center">
                    @foreach ($categoriesAll as $categoryAll)
                        @if ($categoryAll == $category)
                            <a href="/topHeadlines?category={{ $categoryAll }}"
                                class="m-2 text-2xl text-blue-500 hover:underline">{{ strtoupper($categoryAll) }}</a>
                        @else
                            <a href="/topHeadlines?category={{ $categoryAll }}"
                                class="m-2 text-2xl text-black hover:underline">{{ strtoupper($categoryAll) }}</a>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="">

                {{-- Filters (Source) --}}
                <!-- Add a button that toggles the visibility of the form -->
                <button id="toggle-form" class="text-gray-600 rounded-lg bg-white mb-4 text-xl">Filters&#160<i
                        class="fa fa-caret-down text-gray-600"></i></button>

                <!-- Wrap the form in a container with a class of "hidden" to hide it by default -->
                <div id="form-container" class="hidden">
                    <form class="flex items-center mb-8 w-full" action="/topHeadlines">
                        <select class='mr-4 rounded-lg focus:outline-none focus:shadow text-gray-500' name="source"
                            id="source">
                            <option value="none" selected disabled hidden>Select source</option>
                            @foreach ($allSources as $allSource)
                                <option value="{{ $allSource->id }}">{{ $allSource->name }}</option>
                            @endforeach
                        </select>

                        <button type="submit"
                            class="h-12 px-6 text-white rounded-lg bg-red-500 hover:bg-red-400">Submit</button>
                    </form>
                </div>

                <!-- Add a script that toggles the visibility of the form on click -->
                <script>
                    var formContainer = document.getElementById('form-container');
                    var toggleFormButton = document.getElementById('toggle-form');

                    toggleFormButton.addEventListener('click', function() {
                        formContainer.classList.toggle('hidden');
                    });
                </script>

                {{-- Top headlines --}}
                <div class="bg-gray-100 rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold mb-4">Top Headlines
                    </h2>
                    @if (count($newsTop) > 0)
                        @foreach ($newsTop as $index => $newTop)
                            <div class="mb-6">
                                @if ($newTop->urlToImage)
                                    <img width="1600" height="500" src="{{ $newTop->urlToImage }}">
                                @else
                                    {{-- <img width="100" height="200" src="{{asset('pictures/no_image_r.jpeg')}}"> --}}
                                @endif
                                <a href="{{ $newTop->url }}"
                                    class="text-xl font-bold text-blue-500 hover:underline">{{ $newTop->title ?? 'No title' }}</a>
                                <p class="text-gray-600">{{ $newTop->description ?? 'No description' }}</p>
                                <p class="text-gray-700">By {{ $newTop->author ?? 'Unknown author' }}</p>

                                {{-- Favourites --}}
                                {{-- HERE IS USED FUNCTIONALITY OF MATCHING URLS. IT WILL HIDE THE ADD TO FAVOURITE BUTTON --}}
                                @if (array_search($newTop->url, $arrayOfMatchedUrls))
                                    <div class="text-gray-600"><span class="mt-5 font-xl fa fa-star checked text-yellow-400">&#160&#160</span> Added to favorites</div>
                                @else
                                <form method="POST" action="{{ route('favourites.store') }}">
                                        @csrf
                                        <input type="hidden" id="title" name="title"
                                            value="{{ $newTop->title }}">
                                        <input type="hidden" id="url" name="url" value="{{ $newTop->url }}">
                                        <input type="hidden" id="author" name="author"
                                            value="{{ $newTop->author }}">
                                        <input type="hidden" id="description" name="description"
                                            value="{{ $newTop->description }}">
                                        <input type="hidden" id="image" name="image"
                                            value="{{ $newTop->urlToImage }}">
                                        <input type="hidden" id="userId" name="userId"
                                            value={{ auth()->user()->id }}>
                                        <button
                                            class="flex items-center justify-center mt-4 px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:underline">
                                            <i class="fa fa-heart text-red-500"> &#160</i> Add to favorites
                                        </button>
                                    </form>
                                @endif

                                {{-- Comments --}}
                                 <div class="mt-5">
                                    <div class="mt-5">
                                        <form class="mb-4 flex flex-row" method="POST" action="{{ route('comments.store') }}">
                                            @csrf
                                            <div class="flex flex-col mr-2 w-full">
                                                <textarea name="commentText" id="commentText" rows="1" placeholder="Leave your comment here"
                                                          class="text-gray-500 rounded-lg border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                            </div>
                                            <input type="hidden" id="userId" name="userId" value={{ auth()->user()->id }}>
                                            <input type="hidden" id="url" name="url" value="{{ $newTop->url }}">
                                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-400">Submit</button>
                                        </form>
                                    </div>

                                 <div class="mt-4 max-h-0 overflow-hidden transition-all duration-500 ease-in-out"
                                        id="comments-section-{{ $index }}">
                                <!-- Comment 1 -->
                                         @php
                                            $commentCount = 0;
                                            $newTopCount = 0;
                                        @endphp
                                        @foreach ($comments as $comment)
                                        @if ($comment->url == $newTop->url)
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

                        {{-- Pagination --}}
                        @if ($numberOfPages > 1)
                            <div class="mt-6 flex justify-center items-center">
                                @if ($numberOfPages > 1)
                                    <div class="flex flex-wrap justify-center">
                                        @php
                                            $startPage = max($currentPage - 2, 1);
                                            $endPage = min($startPage + 4, $numberOfPages);
                                            if ($endPage - $startPage < 4) {
                                                $startPage = max($endPage - 4, 1);
                                        } @endphp @if ($startPage > 1)
                                            <a href="{{ $url }}?page=1&q={{ $q }}&category={{ $category }}&source={{ $source }}"
                                                class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium">1</a>
                                        @endif

                                        @for ($x = $startPage; $x <= $endPage; $x++)
                                            <a href="{{ $url }}?page={{ $x }}&q={{ $q }}&category={{ $category }}&source={{ $source }}"
                                                class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium @if ($x == $currentPage) font-bold @endif">
                                                {{ $x }}</a>
                                        @endfor
                                        @if ($endPage < $numberOfPages)
                                            @if ($endPage < $numberOfPages - 1)
                                                <span class="mx-1 px-2 py-1 rounded-lg bg-gray-300 text-sm font-medium">
                                                    ...</span>
                                            @endif

                                            <a href="{{ $url }}?page={{ $numberOfPages }}&q={{ $q }}&category={{ $category }}&source={{ $source }}"
                                                class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium">{{ $numberOfPages }}</a>
                                        @endif
                                    </div>
                                @endif
                            </div>

                        @endif
                    @else
                        <p>No news found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>



</x-app-layout>
