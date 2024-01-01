@extends('layouts.presensi')

@section('content')
<div class="row" style="margin-top: 50px">
    <div class="col">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <select name="bulan" id="bulan" class="form-control">
                        <option value="">Bulan</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ $namabulan[$i] }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <select name="tahun" id="tahun" class="form-control">
                        <option value="">Tahun</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 text-center">
        <div class="form-group">
            <button class="btn btn-primary" id="searchdata">Search</button>
        </div>
    </div>
</div>

<!-- Tambahkan tempat untuk menampilkan hasil pencarian -->
<div id="historyResults"></div>
@endsection

@push('myscript')
<script>
    $(function () {
        $("#searchdata").click(function (e) {
            var bulan = $("#bulan").val();
            var tahun = $("#tahun").val();
            $.ajax({
                type: 'POST',
                url: '/gethistory',
                data: {
                    "_token": "{{ csrf_token() }}",
                    bulan: bulan,
                    tahun: tahun,
                },
                cache: false,
                success: function (respond) {
                    $("#historyResults").html(respond);
                }
            });
        });
    });
</script>
@endpush
