@extends('rw.dashboardRw')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Kelola Tujuan Surat</h1>

    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- Button Tambah Data -->
    <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Data</button>

    <!-- Tabel Tujuan Surat -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Tujuan</th>
                <th>Nomor Surat</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tujuanSurat as $tujuan)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $tujuan->nama_tujuan }}</td>
                <td>{{ $tujuan->nomor_surat }}</td>
                <td>{{ $tujuan->deskripsi }}</td>
                <td>
                    <!-- Button Edit -->
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit" data-id="{{ $tujuan->id_tujuan_surat }}">Edit</button>

                    <!-- Button Hapus -->
                    <form action="{{ route('tujuanSurat.destroy', $tujuan->id_tujuan_surat) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Tambah Data -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('tujuanSurat.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Tujuan Surat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_tujuan" class="form-label">Nama Tujuan</label>
                        <input type="text" class="form-control" id="nama_tujuan" name="nama_tujuan" required>
                    </div>
                    <div class="mb-3">
                        <label for="nomor_surat" class="form-label">Nomor Surat</label>
                        <input type="text" class="form-control" id="nomor_surat" name="nomor_surat" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Data -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Edit Tujuan Surat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nama_tujuan" class="form-label">Nama Tujuan</label>
                        <input type="text" class="form-control" id="edit_nama_tujuan" name="nama_tujuan" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nomor_surat" class="form-label">Nomor Surat</label>
                        <input type="text" class="form-control" id="edit_nomor_surat" name="nomor_surat" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Edit Data Tujuan Surat
    $('#modalEdit').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id') // Extract info from data-* attributes
        var modal = $(this)

        $.ajax({
            url: '/tujuan-surat/' + id + '/edit',
            method: 'GET',
            success: function(data) {
                modal.find('#edit_nama_tujuan').val(data.nama_tujuan);
                modal.find('#edit_nomor_surat').val(data.nomor_surat);
                modal.find('#edit_deskripsi').val(data.deskripsi);
                modal.find('form').attr('action', '/tujuan-surat/' + id);
            }
        });
    });
</script>
@endsection

@endsection
