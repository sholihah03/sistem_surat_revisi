<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Surat PDF</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>

<body class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-pink-100">
    <!-- Navbar -->
    @include('komponen.nav')
    <iframe src="{{ $fileUrl }}" style="width: 100%; height: 90vh;" class="border rounded shadow"></iframe>
    <div class="mt-4">
        <a href="{{ $fileUrl }}" target="_blank" class="text-blue-600 hover:underline">Buka di tab baru</a>
    </div>
    @include('components.modal-timeout')
</body>
</html>
