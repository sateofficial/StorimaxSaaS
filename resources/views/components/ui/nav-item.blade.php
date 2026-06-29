@props(['href', 'icon', 'label'])

@php
$active = str_starts_with(request()->path(), ltrim(parse_url($href, PHP_URL_PATH), '/'));

$icons = [
    'grid'         => 'M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z',
    'folder'       => 'M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z',
    'check-square' => 'M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11',
    'file-text'    => 'M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8zM14 2v6h6M16 13H8M16 17H8M10 9H8',
    'image'        => 'M21 19V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2z',
    'bar-chart'    => 'M18 20V10M12 20V4M6 20v-6',
    'users'        => 'M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 7a4 4 0 100 8 4 4 0 000-8z',
    'briefcase'    => 'M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2',
    'layers'       => 'M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5',
];
$path = $icons[$icon] ?? $icons['grid'];
@endphp

<a href="{{ $href }}"
   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
          {{ $active
              ? 'bg-gray-900 text-white font-medium'
              : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
         viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="{{ $path }}"/>
    </svg>
    {{ $label }}
</a>