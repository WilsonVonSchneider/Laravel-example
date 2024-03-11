@if((auth()->user()->role)==1)
<a {{ $attributes->merge(['class' => 'block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-indigo-800 dark:hover:bg-indigo-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-indigo-800 transition duration-150 ease-in-out']) }}>{{ $slot }}</a>
@else
<a {{ $attributes->merge(['class' => 'block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out']) }}>{{ $slot }}</a>
@endif
