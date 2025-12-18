@extends('layouts.dashboard')

@section('title', 'Manajemen Pengguna | Admin Ganesha Hospital')
@section('dashboard-title', 'Manajemen Pengguna')

@section('content')
<x-toast type="success" :message="session('success')" />
<x-toast type="error" :message="session('error')" />

<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Manajemen Pengguna</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola data pengguna sistem</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Total Pengguna</p>
                <p class="text-3xl font-bold text-[#f56e9d]">{{ $users->total() }}</p>
            </div>
        </div>

        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        Cari Pengguna
                    </label>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ request('search') }}"
                        placeholder="Cari berdasarkan email..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" />
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        Role
                    </label>
                    <select
                        name="role"
                        id="role"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent">
                        <option value="">Semua Role</option>
                        <option value="pasien" {{ request('role') === 'pasien' ? 'selected' : '' }}>Pasien</option>
                        <option value="dokter" {{ request('role') === 'dokter' ? 'selected' : '' }}>Dokter</option>
                        <option value="staf" {{ request('role') === 'staf' ? 'selected' : '' }}>Staf</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div id="tableContainer" class="bg-white rounded-lg shadow-md overflow-hidden">
        <div id="tableContent">
            @if($users->isEmpty())
            <div class="p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="mt-4 text-gray-600">Tidak ada data pengguna</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terdaftar</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-medium rounded-full
                                @if($user->role === 'admin') bg-purple-100 text-purple-800
                                @elseif($user->role === 'dokter') bg-blue-100 text-blue-800
                                @elseif($user->role === 'staf') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($user->role === 'pasien' && $user->pasien)
                                    {{ $user->pasien->nama_lengkap }}
                                    @elseif($user->role === 'dokter' && $user->dokter)
                                    {{ $user->dokter->nama_lengkap }}
                                    @elseif($user->role === 'staf' && $user->staf)
                                    {{ $user->staf->nama_lengkap }}
                                    @else
                                    <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $user->created_at->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button
                                    onclick="showResetPasswordModal({{ $user->user_id }}, '{{ $user->email }}')"
                                    class="px-4 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#e05d8c] transition-colors text-sm">
                                    Reset Password
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Menampilkan {{ $users->firstItem() ?? 0 }} sampai {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data
                </div>
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Reset Password -->
<div id="resetPasswordModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div id="modalBackdrop" class="fixed inset-0 bg-black opacity-0 transition-opacity duration-200 lg:left-64" onclick="hideResetPasswordModal()"></div>
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all scale-95 opacity-0 relative z-10" id="modalContent">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-start gap-4">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Reset Password</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Apakah Anda yakin ingin mereset password untuk <strong id="userEmail"></strong>?
                        Password baru akan digenerate secara otomatis dan dikirim ke email pengguna.
                    </p>
                </div>
            </div>
        </div>
        <form id="resetPasswordForm" method="POST" onsubmit="handleResetPassword(event)">
            @csrf
            <div class="p-6 flex gap-3 justify-end">
                <button
                    type="button"
                    onclick="hideResetPasswordModal()"
                    id="cancelBtn"
                    class="px-3 py-2 text-xs bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Batal
                </button>
                <button
                    type="submit"
                    id="resetBtn"
                    class="px-3 py-2 text-xs bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition-colors font-medium shadow-sm flex items-center gap-2 disabled:bg-gray-400 disabled:hover:bg-gray-400 disabled:cursor-not-allowed">
                    <svg id="spinnerIcon" class="hidden animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span id="btnText">Ya, Reset Password</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const roleSelect = document.getElementById('role');
        let debounceTimer = null;

        function debounce(func, delay) {
            return function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(func, delay);
            };
        }

        function fetchData() {
            const params = new URLSearchParams({
                search: searchInput.value,
                role: roleSelect.value
            });

            const url = '{{ route("admin.users.index") }}?' + params.toString();

            const xhr = new XMLHttpRequest();
            xhr.open('GET', url, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.onload = function() {
                if (xhr.status === 200) {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(xhr.responseText, 'text/html');

                    const newContent = doc.getElementById('tableContent');
                    const newTotal = doc.querySelector('.text-3xl.font-bold.text-\\[\\#f56e9d\\]');

                    if (newContent) {
                        document.getElementById('tableContent').innerHTML = newContent.innerHTML;
                    }

                    if (newTotal) {
                        document.querySelector('.text-3xl.font-bold.text-\\[\\#f56e9d\\]').textContent = newTotal.textContent;
                    }

                    window.history.pushState({}, '', url);
                }
            };

            xhr.send();
        }

        const debouncedFetch = debounce(() => fetchData(), 500);

        searchInput.addEventListener('input', debouncedFetch);
        roleSelect.addEventListener('change', debouncedFetch);

        // Reset Password Modal
        window.showResetPasswordModal = function(userId, email) {
            const modal = document.getElementById('resetPasswordModal');
            const modalContent = document.getElementById('modalContent');
            const modalBackdrop = document.getElementById('modalBackdrop');
            const form = document.getElementById('resetPasswordForm');
            const userEmailSpan = document.getElementById('userEmail');

            form.action = `/admin/users/${userId}/reset-password`;
            userEmailSpan.textContent = email;

            modal.classList.remove('hidden');

            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
                modalBackdrop.classList.remove('opacity-0');
                modalBackdrop.classList.add('opacity-20');
            }, 10);
        };

        window.hideResetPasswordModal = function() {
            const modal = document.getElementById('resetPasswordModal');
            const modalContent = document.getElementById('modalContent');
            const modalBackdrop = document.getElementById('modalBackdrop');

            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            modalBackdrop.classList.remove('opacity-0');
            modalBackdrop.classList.add('opacity-20');

            setTimeout(() => {
                modal.classList.add('hidden');
                // Reset button state
                const resetBtn = document.getElementById('resetBtn');
                const cancelBtn = document.getElementById('cancelBtn');
                const spinnerIcon = document.getElementById('spinnerIcon');
                const btnText = document.getElementById('btnText');

                resetBtn.disabled = false;
                cancelBtn.disabled = false;
                spinnerIcon.classList.add('hidden');
                btnText.textContent = 'Ya, Reset Password';
                resetBtn.classList.remove('bg-gray-400', 'hover:bg-gray-400');
                resetBtn.classList.add('bg-[#f56e9d]', 'hover:bg-[#d14a7a]');
            }, 200);
        };

        window.handleResetPassword = function(event) {
            event.preventDefault();

            const resetBtn = document.getElementById('resetBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const spinnerIcon = document.getElementById('spinnerIcon');
            const btnText = document.getElementById('btnText');

            // Disable buttons and show spinner
            resetBtn.disabled = true;
            cancelBtn.disabled = true;
            spinnerIcon.classList.remove('hidden');
            btnText.textContent = 'Memproses...';

            // Submit form
            event.target.submit();
        };
    });
</script>
@endsection