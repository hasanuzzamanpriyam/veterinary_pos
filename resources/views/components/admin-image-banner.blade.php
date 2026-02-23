@php
    $adminUser = \App\Models\User::whereHas('roles', function ($query) {
        $query->where('name', 'Super Admin');
    })->first() ?? \App\Models\User::first();
@endphp

@if ($adminUser && $adminUser->banner_photo_url)
    <div class="w-full bg-gray-100 dark:bg-gray-900">
        <div class="max-w-full">
            <img src="{{ $adminUser->banner_photo_url }}" 
                 alt="Banner" 
                 class="w-full h-auto object-cover"
                 style="max-height: 300px;">
        </div>
    </div>
@endif
