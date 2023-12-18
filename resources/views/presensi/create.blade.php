@extends('layouts.presensi')
<!DOCTYPE html>
<html>
<head>
    <title>Presensi</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />

    <style type="text/css">
        #results { padding:20px; border:1px solid; background:#ccc; }
    </style>
</head>
<body>
<div class="container">
    <form method="POST" action="{{ route('presensi.store') }}">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div id="my_camera"></div>
                <br/>
                <input type=button value="Take Snapshot" onClick="take_snapshot()">
                <input type="hidden" name="image" class="image-tag">
            </div>

            <div class="col-md-6">
                <div id="results">Your captured image will appear here...</div>
            </div>

            <div class="col-md-12 text-center">
                <br/>
                <button class="btn btn-primary" id="absenButton">Absen Keluar</button>
            </div>
        </div>
    </form>
</div>
<script language="JavaScript">

    Webcam.set({
        width: 490,
        height: 350,
        image_format: 'jpeg',
        jpeg_quality: 90
    });
    Webcam.attach( '#my_camera' );

    // Variable untuk mengidentifikasi apakah sudah absen masuk atau belum
    let absenMasuk = false;

    function take_snapshot() {
        Webcam.snap(function(data_uri) {
            $(".image-tag").val(data_uri);
            document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
        });

        // Mengganti teks tombol sesuai dengan kondisi absenMasuk
        if (!absenMasuk) {
            document.getElementById('absenButton').innerHTML = 'Absen Keluar';
            absenMasuk = true;
        } else {
            document.getElementById('absenButton').innerHTML = 'Absen Masuk';
            absenMasuk = false;
        }
        @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

    }

</script>
</body>
</html>
