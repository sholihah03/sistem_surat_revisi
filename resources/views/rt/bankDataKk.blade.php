@extends('rt.dashboardRt')

@section('content')
<div class="container mx-auto p-4 mb-6 pt-20">
    <h1 class="text-2xl font-bold mb-2">Bank Data Kartu Keluarga Warga</h1>
    <p class="mb-6 text-lg text-gray-600">Daftar lengkap data Kartu Keluarga warga yang sudah terdaftar beserta alamat dan foto Kartu Keluarga. Klik gambar KK untuk melihat dan mengunduh dalam format PDF.</p>

    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="table-auto w-full border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 border">No</th>
                    <th class="p-2 border">Nama Lengkap Warga</th>
                    <th class="p-2 border">No KK</th>
                    <th class="p-2 border">Gambar KK</th>
                    <th class="p-2 border">Alamat</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($wargas as $index => $warga)
                    <tr class="border">
                        <td class="p-2 border text-center">{{ $index + 1 }}</td>
                        <td class="p-2 border">{{ $warga->nama_lengkap }}</td>
                        <td class="p-2 border">{{ $warga->no_kk }}</td>
                        <td class="p-2 border">
                            @if ($warga->scan_Kk && $warga->scan_Kk->alamat)
                                {{ $warga->scan_Kk->alamat->nama_jalan }},
                                RT {{ $warga->scan_Kk->alamat->rt_alamat }},
                                RW {{ $warga->scan_Kk->alamat->rw_alamat }},<br>
                                Kel {{ $warga->scan_Kk->alamat->kelurahan }},
                                Kec {{ $warga->scan_Kk->alamat->kecamatan }},
                                Kab/Kota {{ $warga->scan_Kk->alamat->kabupaten_kota }},<br>
                                Provinsi {{ $warga->scan_Kk->alamat->provinsi }},
                                Kode Pos {{ $warga->scan_Kk->alamat->kode_pos }}
                            @else
                                <span class="text-gray-500 italic">Belum tersedia</span>
                            @endif
                        </td>
                        <td class="p-2 border">
                            @if ($warga->scan_Kk && $warga->scan_Kk->path_file_kk)
                            <img
                                src="{{ asset('storage/' . str_replace('public/', '', $warga->scan_Kk->path_file_kk)) }}"
                                alt="KK"
                                class="w-32 cursor-pointer"
                                onclick="showImageModal('{{ asset('storage/' . str_replace('public/', '', $warga->scan_Kk->path_file_kk)) }}', '{{ $warga->nama_lengkap }}')"
                            />
                            @else
                                <span class="text-gray-500 italic">Belum tersedia</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-2 md:px-4 py-3 text-center text-gray-500">
                            <p>Belum ada Bank Data Kartu Keluarga Warga.</p>
                        </td>
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
