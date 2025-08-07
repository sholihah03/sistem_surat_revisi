@extends('rt.dashboardRt')

@section('content')
<div class="container mx-auto p-4 mb-6 pt-20">
    <h1 class="text-2xl font-bold mb-2">Verifikasi Akun Warga</h1>

    <p class="mb-6 text-lg text-gray-700">
        Halaman ini memungkinkan Anda untuk memverifikasi akun warga yang telah mendaftar. Silakan periksa detail mereka dan ambil tindakan yang sesuai.
        <br><strong class="text-red-500">Penting!</strong> Akun harus diverifikasi dalam waktu 24 jam. Jika tidak, warga akan diminta untuk mengunggah ulang dokumen mereka.
    </p>

    <div class="bg-white bg-opacity-80 p-4 md:p-6 rounded-xl shadow w-full">
        <div class="max-h-[400px] overflow-y-auto rounded-lg shadow-md border">
            <table class="min-w-full table-fixed text-xs md:text-sm text-gray-700">
                <thead class="bg-gray-100 sticky top-0 z-10">
                    <tr>
                        <th class="px-2 md:px-4 py-2 text-left w-8">No</th>
                        <th class="px-2 md:px-4 py-2 text-left w-40">Nama Warga</th>
                        <th class="px-2 md:px-4 py-2 text-left w-40">Nama Kepala Keluarga</th>
                        <th class="px-2 md:px-4 py-2 text-left w-24">No KK</th>
                        <th class="px-2 md:px-4 py-2 text-left w-56">Alamat</th>
                        <th class="px-2 md:px-4 py-2 text-left w-32">Waktu Dikirim</th>
                        <th class="px-2 md:px-4 py-2 text-left w-28">Sisa Waktu</th>
                        <th class="px-2 md:px-4 py-2 text-left w-20">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-900">
                    @forelse ($pendingData as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-2 md:px-4 py-2 whitespace-nowrap w-8">{{ $index + 1 }}</td>
                        <td class="px-2 md:px-4 py-2 whitespace-nowrap w-40">
                            {{ $item->nama_pendaftar ?? '-' }}
                        </td>
                        <td class="px-2 md:px-4 py-2 whitespace-nowrap w-40">{{ $item->nama_kepala_keluarga }}</td>
                        <td class="px-2 md:px-4 py-2 whitespace-nowrap w-24">{{ $item->no_kk_scan }}</td>
                        <td class="px-2 md:px-4 py-2 whitespace-nowrap w-56">
                            {{ $item->alamat->nama_jalan ?? '-' }},<br>
                            RT {{ $item->alamat->rt_alamat ?? '-' }}/RW {{ $item->alamat->rw_alamat ?? '-' }},<br>
                            Kel. {{ $item->alamat->kelurahan ?? '-' }},
                            Kec. {{ $item->alamat->kecamatan ?? '-' }}
                        </td>
                        <td class="px-2 md:px-4 py-2 whitespace-nowrap w-32">{{ $item->created_at->format('d-m-Y H:i') }}</td>
                        <td class="px-2 md:px-4 py-2 whitespace-nowrap w-28 text-red-600 font-semibold text-sm min-w-[120px]">
                            <span class="countdown" data-expire="{{ $item->created_at->addHours(24) }}"></span>
                        </td>
                        <td class="px-2 md:px-4 py-2 whitespace-nowrap w-20">
                            <a href="{{ route('detailVerifikasiAkunWarga', $item->id_scan) }}"
                                class="inline-block bg-blue-600 text-white px-3 py-1.5 rounded hover:bg-blue-700 text-xs md:text-sm">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-2 md:px-4 py-3 text-center text-gray-500">
                            <p>✨ Tidak ada data verifikasi saat ini. Pastikan Anda memeriksa lagi nanti! ✨</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800">
        <strong>PERHATIAN:</strong> Anda harus segera memverifikasi akun-akun ini dalam waktu 24 jam. Jika tidak, akun-akun tersebut akan diminta untuk mengunggah ulang dokumen mereka. Pastikan tidak ada yang terlewat!
    </div>
</div>
@endsection
