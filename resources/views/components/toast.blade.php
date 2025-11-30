@props(['type' => 'success', 'message'])

@php
$colors = [
    'success' => 'bg-green-500',
    'error' => 'bg-red-500',
    'warning' => 'bg-yellow-500',
    'info' => 'bg-blue-500',
];

$icons = [
    'success' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
    'error' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
    'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
    'info' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
];

$bgColor = $colors[$type] ?? $colors['info'];
$icon = $icons[$type] ?? $icons['info'];
@endphp

@if($message)
<div id="toast" class="fixed top-4 right-4 z-50 flex items-center gap-3 {{ $bgColor }} text-white px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out opacity-0 translate-y-[-20px]" role="alert">
    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        {!! $icon !!}
    </svg>
    <span class="font-medium">{{ $message }}</span>
    <button onclick="closeToast()" class="ml-4 text-white hover:text-gray-200 focus:outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

<script>
    function closeToast() {
        const toast = document.getElementById('toast');
        if (toast) {
            toast.classList.add('opacity-0', 'translate-y-[-20px]');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    }

    window.addEventListener('DOMContentLoaded', () => {
        const toast = document.getElementById('toast');
        if (toast) {
            setTimeout(() => {
                toast.classList.remove('opacity-0', 'translate-y-[-20px]');
            }, 100);

            setTimeout(() => {
                closeToast();
            }, 5000);
        }
    });
</script>
@endif
