@extends('layouts.presensi ')

@section('header')
<!DOCTYPE html>
<html>
<head>
    <title>Presensi</title>
    <style>

        header {
            background-color: #1310b4;
            color: #fff;
            padding: 10px;
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <p>Edit Profile</p>
    </header>
</body>
</html>
@endsection

@section('content')

<div class="row" style="margin-top 30px">
    <div class="col">
        @php
            $messagesuccess = Session::get('success');
            $messageerror = Session::get('error');
        @endphp
            @if (Session::get('success'))
            <div class="alert alert-success text-center">
                {{ $messagesuccess }}
                </div>
            @endif
            @if (Session::get('error'))
            <div class="alert alert-danger text-center">
                {{ $messageerror }}
                </div>
            @endif
    </div>
</div>

<div class="container">
    <form action="/presensi/{{ $karyawan->nik }}/updateprofile" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="col">
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <input type="text" class="form-control" value="{{$karyawan->nama}}" name="nama" placeholder="Nama Lengkap" autocomplete="off">
                </div>
            </div>

            <div class="form-group boxed">
                <div class="input-wrapper">
                    <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="off">
                </div>
            </div>
            <div class="custom-file-upload" id="fileUpload1">
                <input type="file" name="foto" id="fileuploadInput" accept=".png, .jpg, .jpeg">
                <label for="fileuploadInput">
                    <span>
                        <strong>
                            <ion-icon name="cloud-upload-outline" role="img" class="md hydrated" aria-label="cloud upload outline"></ion-icon>
                            <i>Tap to Upload</i>
                        </strong>
                    </span>
                </label>
            </div>

            <div class="form-group boxed">
                <div class="input-wrapper">
                    <button type="submit" class="btn btn-primary btn-block">
                        <ion-icon name="refresh-outline"></ion-icon>
                        Update
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
