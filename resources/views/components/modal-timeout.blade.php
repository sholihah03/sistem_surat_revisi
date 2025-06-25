<!-- Spinner Loading -->
<div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-[9999] hidden">
    <div class="bg-white p-6 rounded-lg text-center shadow">
        <p class="text-lg font-semibold mb-4 text-gray-800">Sedang diproses, mohon tunggu...</p>
        <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-blue-500 mx-auto"></div>
    </div>
</div>

<!-- Modal Timeout -->
<div id="timeoutModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[9999] hidden">
    <div class="bg-white p-6 rounded-lg shadow text-center w-full max-w-md">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Waktu Habis</h2>
        <p class="mb-6 text-gray-600">Proses memakan waktu terlalu lama. Silakan ulangi kembali.</p>
        <button onclick="location.reload()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Ulangi Proses</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const allForms = document.querySelectorAll('form');

    // Proses submit form
    allForms.forEach(form => {
        form.addEventListener('submit', function () {
            startLoadingTimeout();
        });
    });

    // Proses klik link sidebar
    const sidebarLinks = document.querySelectorAll('#sidebar a:not(.cursor-not-allowed)');

    sidebarLinks.forEach(link => {
        link.addEventListener('click', function () {
            startLoadingTimeout();
        });
    });

    const profileLink = document.getElementById("profileLink");
    if (profileLink) {
        profileLink.addEventListener("click", function (e) {
            e.preventDefault();

            if (loadingOverlay) {
                loadingOverlay.classList.remove("hidden");

                setTimeout(() => {
                    window.location.href = profileLink.getAttribute("href");
                }, 300); // beri waktu render loading
            } else {
                window.location.href = profileLink.getAttribute("href");
            }
        });
    }

    function startLoadingTimeout() {
        const loadingOverlay = document.getElementById('loadingOverlay');
        const timeoutModal = document.getElementById('timeoutModal');

        if (loadingOverlay) loadingOverlay.classList.remove('hidden');

        setTimeout(() => {
            if (loadingOverlay) loadingOverlay.classList.add('hidden');
            if (timeoutModal) timeoutModal.classList.remove('hidden');
        }, 61000);
    }
});
</script>

