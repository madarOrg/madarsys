{{-- components/logo.blade.php --}}
@props([
    'src' => config('app.logo', asset('storage/logo-dark.svg')), 
    'darkSrc' => config('app.logo_dark', asset('storage/logo.svg')), 
    'alt' => config('app.name', 'App Logo'),
    'href' => '/', 
    'showText' => true,
    'text' => config('app.name', 'TutorNet'), 
])

<a href="{{ $href }}" class="flex z-50 items-center">
    <!-- الشعار يتغير حسب الوضع -->
    <img 
        src="{{ $src }}" 
        alt="{{ $alt }}" 
        class="h-8 me-3 dark:hidden"
    >
    <img 
        src="{{ $darkSrc }}" 
        alt="{{ $alt }}" 
        class="h-8 me-3 hidden dark:block"
    >
    @if ($showText)
        <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white hidden sm:block">
            {{ $text }}
        </span>
    @endif
</a>
