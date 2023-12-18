@extends('layouts.admin.tabler')
@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h1 class="page-title">
                    Data Karyawan
                </h1>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-10">
                <div class="row">
                    <div class="col-8">
                        <form action="/karyawan" method="GET">
                            <div class="row">
                                <div class="col-10">
                                     <a href="#" class="btn btn-success"id="tambahKaryawan">Tambah Data Karyawan</a>
                                </div>
                            </div>
                            <div class="row mt-2" >
                                <div class="col-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Masukkan Nama Karyawan"name="nama_karyawan">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-8">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($karyawan as $k)
                                <tr>
                                    <td>{{$loop->iteration + $karyawan->firstItem() -1}}</td>
                                    <td>{{$k->nik}}</td>
                                    <td>{{$k->nama}}</td>
                                    <td>{{$k->jabatan}}</td>
                                    <td>
    <a href="#" class="edit btn btn-success mb-1" nik="{{$k->nik}}">Edit</a>
    <button class="delete-confirm btn btn-danger" data-form=
    "{{ route('karyawan.delete', ['nik' => $k->nik]) }}">Delete</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
 {{-- kodingan pagination --}}
<div class="pagination justify-content-center">
    @if ($karyawan->onFirstPage())
        <button class="btn btn-secondary" disabled><<<<</button>
        <button class="btn btn-secondary" disabled>&lt; Previous</button>
    @else
        <a href="{{ $karyawan->url(1) }}" class="btn btn-secondary"><<</a>
        <a href="{{ $karyawan->previousPageUrl() }}" class="btn btn-success">&lt; Previous</a>
    @endif

    @if ($karyawan->hasMorePages())
        <a href="{{ $karyawan->url(1) }}" class="btn btn-secondary">1</a>
        @if ($karyawan->currentPage() > 3)
            <span class="btn btn-secondary">...</span>
        @endif
        @for ($i = max(2, $karyawan->currentPage() - 2); $i <= min($karyawan->currentPage() + 2, $karyawan->lastPage() - 1); $i++)
            <a href="{{ $karyawan->url($i) }}" class="btn btn-secondary{{ $i == $karyawan->currentPage() ? ' active' : '' }}">{{ $i }}</a>
        @endfor
        @if ($karyawan->currentPage() < $karyawan->lastPage() - 2)
            <span class="btn btn-secondary">...</span>
        @endif
        <a href="{{ $karyawan->url($karyawan->lastPage()) }}" class="btn btn-secondary">{{ $karyawan->lastPage() }}</a>
        <a href="{{ $karyawan->nextPageUrl() }}" class="btn btn-success">Next &gt;</a>
        <a href="{{ $karyawan->url($karyawan->lastPage()) }}" class="btn btn-secondary">>></a>
    @else
        <button class="btn btn-secondary" disabled>Next &gt;</button>
        <button class="btn btn-secondary" disabled>>>></button>
    @endif
</div>
 {{-- input karyawan --}}
 <div class="modal modal-blur fade" id="modal-inputkaryawan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('karyawan.store') }}" method="POST" id="formkaryawan" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon"></span>
                                <input type="text" name="nik" id="nik" class="form-control" placeholder="NIK" required>
                            </div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon"></span>
                                <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama">
                            </div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon"></span>
                                <input type="text" name="jabatan" id="jabatan" class="form-control" placeholder="Jabatan">
                            </div>
                            <div class="form-label">Pilih Foto
                                <input type="file" class="form-control" name="foto">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-10">
                            <div class="form-group">
                                <button class="btn btn-success" type="submit">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- edit karyawan --}}
<div class="modal modal-blur fade" id="modal-editkaryawan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadeditform">

            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function(){
        $("#tambahKaryawan").click(function(){
            $("#modal-inputkaryawan").modal("show");
        })

        $(".edit").click(function(){
            var nik = $(this).attr('nik');
            $.ajax({
                type: 'POST',
                url: '/karyawan/edit',
                cache: false,
                data: {
                    _token: "{{ csrf_token() }}",
                    nik: nik
                },
                success: function(respond){
                    $("#loadeditform").html(respond);
                }
            })
            $("#modal-editkaryawan").modal("show");
        });

        $(".delete-confirm").click(function(e) {
            e.preventDefault();
            var deleteUrl = $(this).data("form");

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data ini akan dihapus secara permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to the delete URL
                    window.location.href = deleteUrl;
                }
            });
        });
    });
</script>
@endpush
