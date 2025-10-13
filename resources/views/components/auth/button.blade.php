@props(['type' => 'submit'])

<button 
    type="{{ $type }}" 
    {!! $attributes->merge(['class' => 'w-full bg-[var(--color-royal-blue-600)] hover:bg-[var(--color-royal-blue-700)] text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-royal-blue-500)]']) !!}
>
    {{ $slot }}
</button>