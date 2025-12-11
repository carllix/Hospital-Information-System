<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Akun Pasien</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: Arial, sans-serif;">
    <div style="max-width: 600px; margin: 32px auto; padding: 16px;">
        <!-- Header -->
        <div style="background-color: #f56e9d; color: white; padding: 32px; border-radius: 8px 8px 0 0; text-align: center;">
            <h1 style="margin: 0; font-size: 24px; font-weight: bold;">
                @if($isReset)
                Reset Password - Ganesha Hospital
                @else
                Selamat Datang di Ganesha Hospital
                @endif
            </h1>
        </div>

        <!-- Content -->
        <div style="background-color: white; padding: 32px; border-radius: 0 0 8px 8px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);">
            <p style="color: #374151; margin-bottom: 16px;">
                Yth. <span style="font-weight: 600; color: #111827;">{{ $namaLengkap }}</span>,
            </p>

            <p style="color: #374151; margin-bottom: 24px;">
                @if($isReset)
                Password Anda telah berhasil direset. Berikut adalah password baru Anda:
                @else
                Akun Anda telah berhasil dibuat di Sistem Informasi Ganesha Hospital. Berikut adalah informasi akun Anda:
                @endif
            </p>

            <!-- Info Box -->
            <div style="background-color: #f9fafb; border-left: 4px solid #f56e9d; padding: 24px; border-radius: 0 8px 8px 0; margin-bottom: 24px;">
                @if($identifier)
                <div style="margin-bottom: 16px;">
                    <p style="font-weight: 600; color: #111827; margin: 0 0 4px 0;">{{ $identifierLabel }}:</p>
                    <p style="color: #f56e9d; font-weight: 600; margin: 0;">{{ $identifier }}</p>
                </div>
                @endif
                <div style="margin-bottom: 16px;">
                    <p style="font-weight: 600; color: #111827; margin: 0 0 4px 0;">Email:</p>
                    <p style="color: #f56e9d; font-weight: 600; margin: 0;">{{ $email }}</p>
                </div>
                <div>
                    <p style="font-weight: 600; color: #111827; margin: 0 0 4px 0;">Password:</p>
                    <p style="color: #f56e9d; font-weight: 600; margin: 0;">{{ $password }}</p>
                </div>
            </div>

            <!-- Warning Box -->
            <div style="background-color: #fefce8; border-left: 4px solid #facc15; padding: 24px; border-radius: 0 8px 8px 0; margin-bottom: 24px;">
                <p style="font-weight: 600; color: #92400e; margin: 0 0 12px 0;">Penting:</p>
                <ul style="margin: 0; padding-left: 20px; color: #374151;">
                    <li style="margin-bottom: 8px;">Simpan password ini dengan aman</li>
                    <li style="margin-bottom: 8px;">Jangan bagikan password Anda kepada siapa pun</li>
                    <li>Segera ubah password setelah login pertama kali</li>
                </ul>
            </div>

            <p style="color: #374151; margin-bottom: 24px;">
                @if($isReset)
                Anda dapat login ke sistem menggunakan email dan password baru di atas. Silakan ubah password Anda setelah login.
                @else
                Anda dapat login ke sistem menggunakan email dan password di atas.
                @endif
            </p>

            <p style="color: #374151; margin: 0;">
                @if($isReset)
                Jika Anda tidak melakukan permintaan reset password, segera hubungi administrator kami.
                @else
                Jika Anda mengalami kesulitan atau memiliki pertanyaan, silakan hubungi petugas pendaftaran kami.
                @endif
            </p>

            <!-- Footer -->
            <div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid #e5e7eb;">
                <p style="font-size: 12px; color: #6b7280; margin: 0 0 4px 0;">
                    Email ini dikirim secara otomatis. Mohon tidak membalas email ini.
                </p>
                <p style="font-size: 12px; color: #6b7280; margin: 0;">
                    &copy; {{ date('Y') }} Ganesha Hospital. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>

</html>