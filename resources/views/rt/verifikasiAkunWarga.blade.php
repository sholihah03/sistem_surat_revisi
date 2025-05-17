@extends('rt.dashboardRt')

@section('content')
<div class="container mx-auto p-4 mb-6 pt-20">
    <h1 class="text-2xl font-bold mb-6">Verifikasi Akun Warga</h1>

    <p class="mb-6 text-sm md:text-base text-gray-700">
        Halaman ini memungkinkan Anda untuk memverifikasi akun warga yang telah mendaftar. Silakan periksa detail mereka dan ambil tindakan yang sesuai. <strong>Penting!</strong> Akun harus diverifikasi dalam waktu 24 jam. Jika tidak, warga akan diminta untuk mengunggah ulang dokumen mereka.
    </p>

    <div class="bg-white bg-opacity-80 p-4 md:p-6 rounded-xl shadow w-full">
        <div class="overflow-x-auto">
            <div class="min-w-full inline-block align-middle overflow-x-auto">
                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full text-xs md:text-sm text-gray-700">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-2 md:px-4 py-2 text-left">No</th>
                                <th class="px-2 md:px-4 py-2 text-left">Nama Warga</th>
                                <th class="px-2 md:px-4 py-2 text-left">Nama Kepala Keluarga</th>
                                <th class="px-2 md:px-4 py-2 text-left">No KK</th>
                                <th class="px-2 md:px-4 py-2 text-left">Alamat</th>
                                <th class="px-2 md:px-4 py-2 text-left">Waktu Dikirim</th>
                                <th>Sisa Waktu</th>
                                <th class="px-2 md:px-4 py-2 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-gray-900">
                            @forelse ($pendingData as $index => $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap">{{ $index + 1 }}</td>
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap">
                                    {{ $item->pendaftaran->first()->nama_lengkap ?? '-' }}
                                </td>
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap">{{ $item->nama_kepala_keluarga }}</td>
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap">{{ $item->no_kk_scan }}</td>
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap">
                                    {{ $item->alamat->nama_jalan ?? '-' }},<br>
                                    RT {{ $item->alamat->rt_alamat ?? '-' }}/RW {{ $item->alamat->rw_alamat ?? '-' }},<br>
                                    Kel. {{ $item->alamat->kelurahan ?? '-' }},
                                    Kec. {{ $item->alamat->kecamatan ?? '-' }}
                                </td>
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap">
                                    {{ $item->created_at->format('d-m-Y H:i') }} <!-- Format waktu -->
                                </td>
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap text-sm min-w-[120px] text-red-600 font-semibold">
                                    <span class="countdown" data-expire="{{ $item->created_at->addHours(24) }}"></span>
                                </td>
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap">
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
        </div>
    </div>

    <div class="mt-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800">
        <strong>PERHATIAN:</strong> Anda harus segera memverifikasi akun-akun ini dalam waktu 24 jam. Jika tidak, akun-akun tersebut akan diminta untuk mengunggah ulang dokumen mereka. Pastikan tidak ada yang terlewat!
    </div>
</div>
@endsection
