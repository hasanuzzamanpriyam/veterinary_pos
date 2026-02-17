<div class="top_nav">
    <div class="header-logo">
        @php
        $adminUser = \App\Models\User::role('Super Admin')->first();
        @endphp

        @if($adminUser && $adminUser->banner_photo_url)
        <img src="{{ $adminUser->banner_photo_url }}" width="100%" height="200" alt="Banner" style="object-fit: cover;">
        @else
        <img src="{{ asset('assets/images/firoz_header.jpg') }}" width="100%" height="auto" alt="Firoz Enterprise">
        @endif
    </div>
</div>