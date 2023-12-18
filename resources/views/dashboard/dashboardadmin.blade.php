@extends('layouts.admin.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Dashboard
                </h2>
                <div class="row">
                    <div class="col-auto" style="margin-top: 20px;">
                        <div class="col-auto">
                            <span class="bg-primary text-white avatar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path></svg>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">
                                Jumlah Data Karyawan
                            </div>
                            <div class="text-secondary">
                                {{ $totalKaryawan }} Orang saat ini
                            </div>
                        </div>
                    </div>

                    <div class="col-auto" style="margin-top: 20px;">
                        <div class="col-auto">
                            <button class="bg-success text-white avatar" onclick="handleButtonClick()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-report" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.697" />
                                    <path d="M18 14v4h4" />
                                    <path d="M18 11v-4a2 2 0 0 0 -2 -2h-2" />
                                    <path d="M8 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                    <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                    <path d="M8 11h4" />
                                    <path d="M8 15h3" />
                                </svg>
                            </button>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">
                                Jumlah Karyawan Hadir
                            </div>
                            <div class="text-secondary">
                                {{ $jumlahKaryawanHadir }} Orang saat ini
                            </div>
                        </div>
                    </div>

                    <div class="col-auto" style="margin-top: 20px;">
                        <div class="col-auto">
                            <button class="bg-danger text-white avatar" onclick="handleButtonClick()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">
                                Jumlah Karyawan Telat
                            </div>
                            <div class="text-danger">
                                {{ count($karyawanTelat) }} Orang
                            </div>
                            @if(count($karyawanTelat) > 0)
                                <div>
                                    <ul>
                                        @foreach($karyawanTelat as $karyawan)
                                            <li>{{ $karyawan->nama }} - {{ $karyawan->jam_masuk }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
