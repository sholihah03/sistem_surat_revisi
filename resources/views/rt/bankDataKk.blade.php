@extends('rt.dashboardRt')

@section('content')
<div class="container mx-auto p-4 mb-6 pt-20">
    <h1 class="text-2xl font-bold mb-2">Bank Data Kartu Keluarga Warga</h1>
    <p class="mb-6 text-lg text-gray-600">Daftar lengkap data Kartu Keluarga warga yang sudah terdaftar beserta alamat dan foto Kartu Keluarga. Klik gambar KK untuk melihat dan mengunduh dalam format PDF.</p>

<div class="overflow-x-auto bg-white rounded-lg shadow-md">
<table class="table-auto w-full border-collapse border border-gray-300">
    <thead class="bg-gray-200 sticky top-0 block">
        <tr class="table w-full table-fixed">
            <th class="p-2 border w-12">No</th>
            <th class="p-2 border w-36">Nama Warga</th>
            <th class="p-2 border w-40">No KK</th>
            <th class="p-2 border">Alamat</th>
            <th class="p-2 border w-32">Gambar KK</th>
        </tr>
    </thead>
        <tbody class="max-h-[350px] overflow-y-auto block w-full">
            @forelse ($scanKKs as $index => $scanKK)
    <tr class="border flex w-full">
        <td class="p-2 border text-center w-12">{{ $index + 1 }}</td>

        <!-- Nama lengkap warga yang punya scan_kk ini (semua ditampilkan) -->
        <td class="p-2 border w-36">
            @foreach ($scanKK->wargas as $warga)
                * {{ $warga->nama_lengkap }}<br>
            @endforeach
        </td>

        <!-- No KK tampil sekali -->
        <td class="p-2 border w-40">{{ $scanKK->no_kk_scan ?? '-' }}</td>

        <!-- Alamat tampil sekali -->
        <td class="p-2 border flex-1 whitespace-normal break-words">
            @if ($scanKK->alamat)
                {{ $scanKK->alamat->nama_jalan }},
                RT {{ $scanKK->alamat->rt_alamat }},
                RW {{ $scanKK->alamat->rw_alamat }},<br>
                Kel {{ $scanKK->alamat->kelurahan }},
                Kec {{ $scanKK->alamat->kecamatan }},
                Kab/Kota {{ $scanKK->alamat->kabupaten_kota }},<br>
                Provinsi {{ $scanKK->alamat->provinsi }},
                Kode Pos {{ $scanKK->alamat->kode_pos }}
            @else
                <span class="text-gray-500 italic">Belum tersedia</span>
            @endif
        </td>

        <!-- Gambar KK tampil sekali -->
        <td class="p-2 border w-32">
            @if ($scanKK->path_file_kk)
                <img
                    src="{{ asset('storage/' . str_replace('public/', '', $scanKK->path_file_kk)) }}"
                    alt="KK"
                    class="max-w-full max-h-20 cursor-pointer mx-auto"
                    onclick="showImageModal('{{ asset('storage/' . str_replace('public/', '', $scanKK->path_file_kk)) }}', 'Kartu Keluarga')"
                />
            @else
                <span class="text-gray-500 italic">Belum tersedia</span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="p-4 text-center text-gray-500">Belum ada Bank Data Kartu Keluarga Warga.</td>
    </tr>
@endforelse

        </tbody>
    </table>
</div>

</div>

<!-- Modal untuk menampilkan gambar dan tombol download -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="relative bg-white p-4 rounded shadow-lg">
        <button onclick="closeImageModal()" class="absolute top-0 right-0 m-2 text-black text-2xl font-bold">&times;</button>
        <img id="modalImage" src="" alt="Preview KK" class="max-h-[80vh] mb-4" style="max-width: 100%;">
        <div class="text-center">
            <button onclick="downloadPdf()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                Download PDF
            </button>
        </div>
    </div>
</div>

<script>
    let currentImageUrl = '';
    let currentWargaName = '';

    function showImageModal(imageUrl, wargaName) {
        currentImageUrl = imageUrl;
        currentWargaName = wargaName;
        document.getElementById('modalImage').src = imageUrl;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.getElementById('modalImage').src = '';
        currentImageUrl = '';
        currentWargaName = '';
    }

    document.getElementById('imageModal').addEventListener('click', function (e) {
        if (e.target === this) closeImageModal();
    });

    async function downloadPdf() {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF();

        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.src = currentImageUrl;

        img.onload = function () {
            const imgProps = pdf.getImageProperties(img);
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

            pdf.addImage(img, 'JPEG', 0, 0, pdfWidth, pdfHeight);

            const cleanName = currentWargaName.replace(/[^a-zA-Z0-9\s]/g, '').replace(/\s+/g, ' ').trim();
            const fileName = `kartu keluarga ${cleanName}.pdf`;

            pdf.save(fileName);
        };
    }
</script>


@endsection
