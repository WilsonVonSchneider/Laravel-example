<x-app-layout>
    <div class="py-12">
        <x-flash-message />
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="mb-5 text-xl font-bold"><a class="hover:underline" href="/admin/users/{{$user->id}}">{{ $user->name }}</a> <a class="hover:underline" href="/admin/users/{{$user->id}}/logs">logs</a></h2>
            {{-- Filters (Source) --}}
                <!-- Add a button that toggles the visibility of the form -->
                <button id="toggle-form" class="text-gray-600 rounded-lg bg-white mb-5 text-xl">Filters&#160<i
                    class="fa fa-caret-down text-gray-600"></i></button>

            <!-- Wrap the form in a container with a class of "hidden" to hide it by default -->
            <div id="form-container" class="hidden mb-5">
                <form class="flex items-center mb-5 w-full" action="/admin/users/{{ $user->id }}/logs">
                    <label for="action" class="mr-4">Action:</label>
                    <select class='mr-4 rounded-lg focus:outline-none focus:shadow text-gray-500' name="action"
                        id="action">
                        <option value="none" selected disabled hidden>Select action</option>
                        @foreach ($actionColors as $index => $actionColor)
                            <option value="{{ $index }}">{{ $index }}</option>
                        @endforeach
                    </select>
                    <label for="from_date" class="mr-4">From:</label>
                    <input type="date" name="fromDate" id="fromDate"
                        class="w-full mr-4 rounded-lg focus:outline-none focus:shadow text-gray-500">
                    <label for="toDate" class="mr-4">To:</label>
                    <input type="date" name="toDate" id="toDate"
                        class="w-full mr-4 rounded-lg focus:outline-none focus:shadow text-gray-500">
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
            @if (count($logs))
                <table class="table-auto w-full">
                    <thead>
                        <tr>

                            <th class="px-4 py-2">Action</th>
                            <th class="px-4 py-2">Description</th>
                            <th class="px-4 py-2">Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $index => $log)
                            <tr>

                                <td class="border px-4 py-2">
                                    <div
                                        class="bg-{{$actionColors[$log->action]}}-500 text-white rounded-md px-2 py-1">
                                        {{ $log->action }}</div>
                                </td>
                                <td class="border px-4 py-2">{{ $log->description }}</td>
                                <td class="border px-4 py-2">{{ $log->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No comments found</p>
            @endif
            <div class="mt-20">
                {{ $logs->appends(['perPage' => $perPage])->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
