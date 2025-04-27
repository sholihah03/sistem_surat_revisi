let countdown = 30;
        const timerEl = document.getElementById('timer');
        const resendBtn = document.getElementById('resendBtn');

        const interval = setInterval(() => {
            countdown--;
            timerEl.textContent = countdown;

            if (countdown <= 0) {
                clearInterval(interval);
                resendBtn.disabled = false;
                resendBtn.textContent = 'Kirim Ulang OTP';
            }
        }, 1000);
