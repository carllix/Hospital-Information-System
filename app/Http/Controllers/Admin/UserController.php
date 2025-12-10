<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordNotification;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('is_deleted', false)->with(['pasien', 'dokter', 'staf']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereRaw('LOWER(email) LIKE ?', ['%' . strtolower($search) . '%']);
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:pasien,dokter,staf,admin',
        ]);

        try {
            $user = User::create([
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => $validated['role'],
                'is_deleted' => false,
            ]);

            return redirect()->route('admin.users.show', $user->user_id)
                ->with('success', 'User berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $user = User::with(['pasien', 'dokter', 'staf'])->findOrFail($id);

        if ($user->is_deleted) {
            abort(404);
        }

        return view('admin.users.show', compact('user'));
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->is_deleted) {
            abort(404);
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        if ($user->is_deleted) {
            abort(404);
        }

        $validated = $request->validate([
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id, 'user_id')],
            'role' => 'required|in:pasien,dokter,staf,admin',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        try {
            $updateData = [
                'email' => $validated['email'],
                'role' => $validated['role'],
            ];

            if ($request->filled('password')) {
                $updateData['password'] = $validated['password'];
            }

            $user->update($updateData);

            return redirect()->route('admin.users.show', $user->user_id)
                ->with('success', 'Data user berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->user_id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        try {
            $user->update(['is_deleted' => true]);

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function resetPassword(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->is_deleted) {
            abort(404);
        }

        try {
            // Generate random password
            $newPassword = Str::random(12);

            // Update user password
            $user->update([
                'password' => Hash::make($newPassword)
            ]);

            // Send email notification
            Mail::to($user->email)->send(new PasswordNotification($newPassword));

            return back()->with('success', 'Password berhasil direset dan dikirim ke email pengguna!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
