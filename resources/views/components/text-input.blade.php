@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-white dark:text-gray-500 focus:border-indigo-500 dark:focus:border-blue-500 focus:ring-indigo-500 dark:focus:ring-blue-500 rounded-md shadow-sm']) !!}>
