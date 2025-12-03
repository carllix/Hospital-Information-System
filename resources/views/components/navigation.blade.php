@php
$roleMenus = [
    'pasien' => [
        ['name' => 'Dashboard', 'route' => '/pasien/dashboard', 'icon' => 'dashboard'],
        ['name' => 'Pembayaran', 'route' => '/pasien/pembayaran', 'icon' => 'payment'],
        ['name' => 'Rekam Medis', 'route' => '/pasien/rekam-medis', 'icon' => 'medical'],
        ['name' => 'Health Monitoring', 'route' => '/pasien/health-monitoring', 'icon' => 'health'],
        ['name' => 'Jadwal Dokter', 'route' => '/pasien/jadwal-dokter', 'icon' => 'medical'],
        ['name' => 'Profil', 'route' => '/pasien/profile', 'icon' => 'profile'],
    ],
    'pendaftaran' => [
        ['name' => 'Dashboard', 'route' => '/pendaftaran/dashboard', 'icon' => 'dashboard'],
        ['name' => 'Pendaftaran Pasien Baru', 'route' => '/pendaftaran/pasien-baru', 'icon' => 'profile'],
        ['name' => 'Pendaftaran Kunjungan', 'route' => '/pendaftaran/kunjungan', 'icon' => 'medical'],
        ['name' => 'Data Pasien', 'route' => '/pendaftaran/data-pasien', 'icon' => 'profile'],
        ['name' => 'Antrian Hari Ini', 'route' => '/pendaftaran/antrian', 'icon' => 'dashboard'],
        ['name' => 'Jadwal Dokter', 'route' => '/pendaftaran/jadwal-dokter', 'icon' => 'profile'],
        ['name' => 'Riwayat Pendaftaran', 'route' => '/pendaftaran/riwayat', 'icon' => 'medical'],
        ['name' => 'Profil Saya', 'route' => '/pendaftaran/profile', 'icon' => 'profile'],
    ],
    'dokter' => [
        ['name' => 'Dashboard', 'route' => '/dokter/dashboard', 'icon' => 'dashboard'],
        ['name' => 'Antrian Pasien', 'route' => '/dokter/antrian', 'icon' => 'medical'],
        ['name' => 'Riwayat Pemeriksaan', 'route' => '/dokter/riwayat', 'icon' => 'medical'],
        ['name' => 'Profil Saya', 'route' => '/dokter/profile', 'icon' => 'profile'],
    ],
    'apoteker' => [
        ['name' => 'Dashboard', 'route' => '/farmasi/dashboard', 'icon' => 'dashboard'],
        ['name' => 'Daftar Resep', 'route' => '/farmasi/resep', 'icon' => 'medical'],
        ['name' => 'Stok Obat', 'route' => '/farmasi/stok-obat', 'icon' => 'health'],
        ['name' => 'Laporan', 'route' => '/farmasi/laporan', 'icon' => 'payment'],
    ],
    'lab' => [
        ['name' => 'Dashboard', 'route' => '/lab/dashboard', 'icon' => 'dashboard'],
        ['name' => 'Daftar Permintaan', 'route' => '/lab/permintaan', 'icon' => 'medical'],
        ['name' => 'Riwayat Pemeriksaan', 'route' => '/lab/riwayat', 'icon' => 'health'],
        ['name' => 'Laporan', 'route' => '/lab/laporan', 'icon' => 'payment'],
        ['name' => 'Profil Saya', 'route' => '/lab/profile', 'icon' => 'profile'],
    ],
    'kasir_klinik' => [
        ['name' => 'Dashboard', 'route' => '/kasir-klinik/dashboard', 'icon' => 'dashboard'],
        ['name' => 'Riwayat Transaksi', 'route' => '/kasir-klinik/riwayat', 'icon' => 'medical'],
        ['name' => 'Laporan Keuangan', 'route' => '/kasir-klinik/laporan', 'icon' => 'payment'],
    ],
    'kasir_apotek' => [
        ['name' => 'Dashboard', 'route' => '/kasir-apotek/dashboard', 'icon' => 'dashboard'],
        ['name' => 'Riwayat Transaksi', 'route' => '/kasir-apotek/riwayat', 'icon' => 'medical'],
        ['name' => 'Laporan Keuangan', 'route' => '/kasir-apotek/laporan', 'icon' => 'payment'],
    ],
    'kasir_lab' => [
        ['name' => 'Dashboard', 'route' => '/kasir-lab/dashboard', 'icon' => 'dashboard'],
        ['name' => 'Riwayat Transaksi', 'route' => '/kasir-lab/riwayat', 'icon' => 'medical'],
        ['name' => 'Laporan Keuangan', 'route' => '/kasir-lab/laporan', 'icon' => 'payment'],
    ],
];

$menus = $roleMenus[Auth::user()->role] ?? [];
$currentRoute = request()->path();
@endphp

<aside class="flex flex-col fixed inset-y-0 w-68 shadow-lg z-40" style="background: linear-gradient(to bottom, #f56e9d, #d14a7a);">
    <div class="flex items-center space-x-3 px-4 py-6 border-b border-gray-200 border-opacity-50">
        <img src="{{ asset('images/GaTal-logo.png') }}" alt="Logo" class="w-12">
        <div>
            <h1 class="text-xl font-bold text-white">Ganesha Hospital</h1>
        </div>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        @foreach($menus as $menu)
            @php
                // Check if current route matches exactly or starts with menu route
                $isActive = '/' . $currentRoute === $menu['route'] || str_starts_with('/' . $currentRoute, $menu['route'] . '/');
            @endphp
            <a href="{{ $menu['route'] }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 text-white hover:bg-[#d4537b] {{ $isActive ? 'bg-[#d4537b] shadow-lg font-semibold' : '' }}">
                <x-menu-icon :icon="$menu['icon']" :active="$isActive" size="small" />
                <span class="text-sm font-medium">{{ $menu['name'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="border-t border-gray-200 border-opacity-50 px-4 py-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-white hover:bg-[#c7406a] hover:cursor-pointer w-full transition-all duration-200">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="text-sm font-medium">Logout</span>
            </button>
        </form>
    </div>
</aside>