@php
$user = Auth::user();
$role = $user->role;
$bagian = $role === 'staf' ? $user->staf?->bagian : null;

$stafMenus = [
'pendaftaran' => [
['name' => 'Dashboard', 'route' => '/pendaftaran/dashboard', 'icon' => 'dashboard'],
['name' => 'Pendaftaran Pasien Baru', 'route' => '/pendaftaran/pasien-baru', 'icon' => 'user-plus'],
['name' => 'Pendaftaran Kunjungan', 'route' => '/pendaftaran/kunjungan', 'icon' => 'clipboard-check'],
['name' => 'Data Pasien', 'route' => '/pendaftaran/data-pasien', 'icon' => 'users'],
['name' => 'Daftar Antrian', 'route' => '/pendaftaran/antrian', 'icon' => 'clipboard-list'],
['name' => 'Jadwal Dokter', 'route' => '/pendaftaran/jadwal-dokter', 'icon' => 'calendar'],
['name' => 'Riwayat Pendaftaran', 'route' => '/pendaftaran/riwayat', 'icon' => 'clock'],
],
'farmasi' => [
['name' => 'Dashboard', 'route' => '/farmasi/dashboard', 'icon' => 'dashboard'],
['name' => 'Daftar Resep', 'route' => '/farmasi/resep', 'icon' => 'document'],
['name' => 'Stok Obat', 'route' => '/farmasi/stok-obat', 'icon' => 'box'],
],
'laboratorium' => [
['name' => 'Dashboard', 'route' => '/lab/dashboard', 'icon' => 'dashboard'],
['name' => 'Daftar Permintaan', 'route' => '/lab/permintaan', 'icon' => 'medical'],
['name' => 'Riwayat Pemeriksaan', 'route' => '/lab/riwayat', 'icon' => 'health'],
['name' => 'Laporan', 'route' => '/lab/laporan', 'icon' => 'payment'],
],
'kasir' => [
['name' => 'Dashboard', 'route' => '/kasir/dashboard', 'icon' => 'dashboard'],
['name' => 'Riwayat Transaksi', 'route' => '/kasir/riwayat', 'icon' => 'medical'],
['name' => 'Laporan Keuangan', 'route' => '/kasir/laporan', 'icon' => 'payment'],
],
];

$roleMenus = [
'pasien' => [
['name' => 'Dashboard', 'route' => '/pasien/dashboard', 'icon' => 'dashboard'],
['name' => 'Pendaftaran Kunjungan', 'route' => '/pasien/pendaftaran-kunjungan', 'icon' => 'plus'],
['name' => 'Jadwal Kunjungan', 'route' => '/pasien/jadwal-kunjungan', 'icon' => 'calendar'],
['name' => 'Rekam Medis', 'route' => '/pasien/rekam-medis', 'icon' => 'document'],
['name' => 'Pembayaran', 'route' => '/pasien/pembayaran', 'icon' => 'payment'],
['name' => 'Health Monitoring', 'route' => '/pasien/health-monitoring', 'icon' => 'health'],
['name' => 'Jadwal Dokter', 'route' => '/pasien/jadwal-dokter', 'icon' => 'clock'],
['name' => 'Profil', 'route' => '/pasien/profile', 'icon' => 'profile'],
],
'dokter' => [
['name' => 'Dashboard', 'route' => '/dokter/dashboard', 'icon' => 'dashboard'],
['name' => 'Antrian Pasien', 'route' => '/dokter/antrian', 'icon' => 'clipboard-list'],
['name' => 'Riwayat Pemeriksaan', 'route' => '/dokter/riwayat', 'icon' => 'clock'],
['name' => 'Profil Saya', 'route' => '/dokter/profile', 'icon' => 'profile'],
],
'admin' => [
['name' => 'Dashboard', 'route' => '/admin/dashboard', 'icon' => 'dashboard'],
['name' => 'Dokter', 'route' => '/admin/dokter', 'icon' => 'user-circle'],
['name' => 'Staf', 'route' => '/admin/staf', 'icon' => 'user-group'],
['name' => 'Jadwal Dokter', 'route' => '/admin/jadwal-dokter', 'icon' => 'calendar'],
['name' => 'Obat', 'route' => '/admin/obat', 'icon' => 'beaker'],
['name' => 'Layanan', 'route' => '/admin/layanan', 'icon' => 'clipboard-list'],
['name' => 'Pengguna', 'route' => '/admin/users', 'icon' => 'users'],
],
];

if ($role === 'staf' && $bagian) {
$menus = $stafMenus[$bagian] ?? [];
} else {
$menus = $roleMenus[$role] ?? [];
}

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
        $isActive = '/' . $currentRoute === $menu['route'] || str_starts_with('/' . $currentRoute, $menu['route'] . '/');

        // Special handling for Stok Obat menu - also activate for tambah and edit routes
        if ($menu['route'] === '/farmasi/stok-obat' && (
        str_contains($currentRoute, 'farmasi/obat/') ||
        str_contains($currentRoute, 'farmasi/obat/tambah')
        )) {
        $isActive = true;
        }

        if ($menu['route'] === '/kasir/dashboard' && (
        str_contains($currentRoute, 'kasir/buat-tagihan') ||
        str_contains($currentRoute, 'kasir/detail')
        )) {
        $isActive = true;
        }

        if ($menu['route'] === '/kasir/riwayat' && str_contains($currentRoute, 'kasir/invoice')) {
        $isActive = true;
        }
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="text-sm font-medium">Logout</span>
            </button>
        </form>
    </div>
</aside>