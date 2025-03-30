<header class="mb-0">
    <div class="flex items-center justify-between mb-0">
        <div class="text-right">
            <h1 class="text-2xl font-bold">{{ $company->name ?? 'غير متاح' }}</h1>
            <h3 class="text-lg">المستودع: {{ $warehouse->name ?? 'غير متاح' }}</h3>
        </div>
        <img src="{{ asset('storage/' . $company->logo) }}" 
            class="w-16 h-16 rounded-full">
    </div>
    {{ $slot }} 
</header>
