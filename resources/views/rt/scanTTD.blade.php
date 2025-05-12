@extends('rt.dashboardRt')

@section('content')
<div class="container mx-auto p-4 mb-6 pt-20">
    <div class="max-w-xl mx-auto bg-white rounded-2xl shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-4 text-center text-gray-800">Upload Scan Tanda Tangan</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('scanTtdRtUpload') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label for="scan_ttd" class="block text-sm font-medium text-gray-700 mb-1">Pilih Gambar Scan Tanda Tangan</label>
                <input type="file" name="ttd_digital" accept="image/*" class="block w-full text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-lg cursor-pointer p-2" required>
            </div>

            <div class="flex justify-center">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all">
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
