@extends('rt.dashboardRt')

@section('content')

<div class="container mx-auto p-4 mb-6 pt-20">
    <h1 class="text-2xl font-bold mb-6">Verifikasi Akun Warga</h1>

    @foreach ($pendingData as $item)
    <div class="bg-white shadow-md rounded-lg p-4 mb-6 border border-gray-200">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="md:w-1/2">
                <p><strong>Nama Kepala Keluarga:</strong> {{ $item->nama_kepala_keluarga }}</p>
                <p><strong>No KK:</strong> {{ $item->no_kk_scan }}</p>
                <p><strong>Alamat:</strong></p>
                <ul class="ml-4 list-disc">
                    <li>{{ $item->alamat->nama_jalan ?? '-' }}</li>
                    <li>RT/RW: {{ $item->alamat->rt_alamat ?? '-' }}/{{ $item->alamat->rw_alamat ?? '-' }}</li>
                    <li>Kelurahan: {{ $item->alamat->kelurahan ?? '-' }}</li>
                    <li>Kecamatan: {{ $item->alamat->kecamatan ?? '-' }}</li>
                    <li>Kab/Kota: {{ $item->alamat->kabupaten_kota ?? '-' }}</li>
                    <li>Provinsi: {{ $item->alamat->provinsi ?? '-' }}</li>
                    <li>Kode Pos: {{ $item->alamat->kode_pos ?? '-' }}</li>
                </ul>
            </div>
            <div class="md:w-1/2">
                {{-- str_replace('public/', '', $item->path_file_kk) menghapus public/ dari path yang disimpan di database. --}}
                <img src="{{ asset('storage/' . str_replace('public/', '', $item->path_file_kk)) }}" alt="Foto KK" class="w-80 h-auto border border-red-500" />
            </div>
        </div>
        <div class="mt-4 flex gap-3">
            <form method="POST" action="{{ route('verifikasiAkunWarga.disetujui', $item->id_scan) }}">
                @csrf
                <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Setujui</button>
            </form>
            <form method="POST" action="{{ route('verifikasiAkunWarga.ditolak', $item->id_scan) }}">
                @csrf
                <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Tolak</button>
            </form>
        </div>
    </div>
    @endforeach
</div>

@endsection
