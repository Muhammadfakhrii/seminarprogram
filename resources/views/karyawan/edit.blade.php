<form action="/karyawan/{{ $karyawan->nik }}/update" method="POST" id="formkaryawan" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon"></span>
                <input type="text" readonly value="{{ $karyawan->nik }}" name="nik" id="nik" class="form-control" placeholder="NIK" required>
            </div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon"></span>
                <input type="text" value="{{ $karyawan->nama }}" name="nama" id="nama" class="form-control" placeholder="Nama">
            </div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon"></span>
                <input type="text" value="{{ $karyawan->jabatan }}" name="jabatan" id="jabatan" class="form-control" placeholder="Jabatan">
            </div>
            <div class="form-label">Pilih Foto
                <input type="file" class="form-control" name="foto">
                <input type="hidden" name="old_foto" value="{{$karyawan->foto}}">
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
