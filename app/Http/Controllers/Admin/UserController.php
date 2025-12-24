<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordNotification;

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

            // Get nama lengkap based on role
            $namaLengkap = match ($user->role) {
                'pasien' => $user->pasien?->nama_lengkap ?? 'User',
                'dokter' => $user->dokter?->nama_lengkap ?? 'User',
                'staf' => $user->staf?->nama_lengkap ?? 'User',
                default => 'User'
            };

            // Get identifier based on role
            $identifier = match ($user->role) {
                'pasien' => $user->pasien?->no_rekam_medis,
                'dokter' => $user->dokter?->nip_rs,
                'staf' => $user->staf?->nip_rs,
                default => null
            };

            // Send email notification
            Mail::to($user->email)->send(new PasswordNotification(
                $namaLengkap,
                $user->email,
                $newPassword,
                $user->role,
                $identifier,
                true // isReset = true untuk reset password
            ));

            return back()->with('success', 'Password berhasil direset dan dikirim ke email pengguna!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
