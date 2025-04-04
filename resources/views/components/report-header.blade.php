<header class="mb-4 border-b pb-2">
    <div class="flex items-center justify-between">
        <div class="text-right">
            <h1 class="text-2xl font-bold">{{ $userCompany->name ?? 'غير متاح' }}</h1>
            <h3 class="text-lg">المستودع: {{ $userWarehouse->name ?? 'غير متاح' }}</h3>
            {{-- <h3 class="text-lg">الفرع: {{ $userBranch->name ?? 'غير متاح' }}</h3> --}}
        </div>
        <div class="flex-shrink-0">
            <img src="{{ asset('storage/' . ($companyLogo ?? 'default_logo.png')) }}" alt="Company Logo" class="w-16 h-16 rounded-full">
        </div>
    </div>
    <div class="mt-2">
        {{ $slot }}
    </div>
</header>
