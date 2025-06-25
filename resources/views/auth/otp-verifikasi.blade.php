<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center px-4" style="background-image: url('{{ asset('images/background login.png') }}')">

    <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 space-y-5">
        <h2 class="text-2xl font-bold text-center text-gray-800">Verifikasi Kode OTP</h2>
        <p class="text-center text-sm text-gray-600">Kode telah dikirim ke WhatsApp dan Email Anda.</p>

        <form method="POST" action="{{ route('otp.verifikasi') }}" class="space-y-4">
            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-2 rounded mb-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @csrf

            <!-- OTP Input -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode OTP</label>
                <div class="flex justify-center gap-2">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="\d*" name="otp[]"
                            class="w-12 h-12 text-center text-xl border border-blue-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 otp-input">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="\d*" name="otp[]"
                            class="w-12 h-12 text-center text-xl border border-blue-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 otp-input">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="\d*" name="otp[]"
                            class="w-12 h-12 text-center text-xl border border-blue-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 otp-input">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="\d*" name="otp[]"
                            class="w-12 h-12 text-center text-xl border border-blue-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 otp-input">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="\d*" name="otp[]"
                            class="w-12 h-12 text-center text-xl border border-blue-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 otp-input">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="\d*" name="otp[]"
                            class="w-12 h-12 text-center text-xl border border-blue-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 otp-input">
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-yellow-400 hover:bg-yellow-500 text-white py-2 rounded-lg font-semibold transition">
                Verifikasi
            </button>

            <!-- Kirim Ulang -->
            <div class="text-center">
                <p class="text-sm text-gray-600">Belum menerima kode?</p>
                <button type="button" id="resendBtn" class="text-blue-600 hover:underline text-sm font-medium mt-1 disabled:opacity-50" disabled>
                    Kirim Ulang OTP (<span id="timer">60</span> detik)
                </button>
                <p id="resend-message" class="text-sm text-green-600 mt-1 hidden">Kode OTP telah dikirim ulang.</p>
            </div>
        </form>
    </div>

    @include('components.modal-timeout')

    <script>
            let countdown = 60;
    let interval; // Ubah dari const ke let

    const timerEl = document.getElementById('timer');
    const resendBtn = document.getElementById('resendBtn');
    const message = document.getElementById('resend-message');

    function startCountdown() {
        clearInterval(interval); // hentikan interval sebelumnya jika ada
        countdown = 60;
        timerEl.textContent = countdown;
        resendBtn.disabled = true;
        resendBtn.textContent = `Kirim Ulang OTP (${countdown} detik)`;

        interval = setInterval(() => {
            countdown--;
            timerEl.textContent = countdown;
            resendBtn.textContent = `Kirim Ulang OTP (${countdown} detik)`;
            if (countdown <= 0) {
                clearInterval(interval);
                resendBtn.disabled = false;
                resendBtn.textContent = 'Kirim Ulang OTP';
            }
        }, 1000);
    }

    // Jalankan countdown saat halaman pertama kali dimuat
    startCountdown();

    resendBtn.addEventListener('click', () => {
        resendBtn.disabled = true;
        resendBtn.textContent = 'Mengirim...';

        fetch('{{ route("otp.kirimUlang") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({})
        })
        .then(res => res.json())
        .then(data => {
            message.classList.remove('hidden');
            message.textContent = data.message;

            // Mulai ulang countdown
            startCountdown();
        })
        .catch(error => {
            console.error('Error:', error);
            resendBtn.disabled = false;
            resendBtn.textContent = 'Kirim Ulang OTP';
        });
    });

            // Ambil semua input dengan class 'otp-input'
    const inputs = document.querySelectorAll('.otp-input');

    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            const value = e.target.value;

            // Jika paste 6 digit sekaligus
            if (value.length === 6) {
                for (let i = 0; i < 6; i++) {
                    inputs[i].value = value[i];
                }
                inputs[5].focus();
                return;
            }

            // Jika hanya satu digit, langsung pindah ke kolom berikutnya
            if (value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                inputs[index - 1].focus();
            }
        });

        // Optional: Select input saat fokus
        input.addEventListener('focus', () => {
            input.select();
        });
    });

    // Deteksi paste dari clipboard
    document.querySelector('.flex').addEventListener('paste', function (e) {
        const paste = (e.clipboardData || window.clipboardData).getData('text');
        if (paste.length === 6 && /^\d+$/.test(paste)) {
            for (let i = 0; i < 6; i++) {
                inputs[i].value = paste[i];
            }
            inputs[5].focus();
        }
        e.preventDefault();
    });
    </script>


</body>
</html>
