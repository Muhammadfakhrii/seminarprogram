@extends('layouts.presensi')

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section" id="user-section">
        <div id="user-detail">
            <div class="avatar">
                @if (!empty(Auth::guard('karyawan')->user()->foto))
                    @php
                        $path = 'assets/foto/karyawan/' . Auth::guard('karyawan')->user()->foto;
                    @endphp
                    <img src="{{ asset($path) }}" alt="Foto Profil" class="imaged w64 rounded" id="user-avatar">
                @else
                    <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="avatar" class="imaged w64 rounded" id="user-avatar">
                @endif
            </div>
            <div id="user-info">
                <h2 id="user-name">{{ Auth::guard('karyawan')->user()->nama }}</h2>
                <span id="user-role"></span>
            </div>
        </div>
    </div>


    <div class="section mt-2" id="presence-section">
        <div class="todaypresence">
            <div class="row">
                <div class="col-6">
                    <div class="card gradasigreen">
                        <div class="card-body">
                            <div class="presencecontent">
                                <div class="iconpresence">
                                    <ion-icon name="camera"></ion-icon>
                                </div>
                                <div class="presencedetail">
                                    <h4 class="presencetitle">Masuk</h4>
                                    <span>{{ $presensisekarang != null ? $presensisekarang->jam_masuk : 'Belum Absen'}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card gradasired">
                        <div class="card-body">
                            <div class="presencecontent">
                                <div class="iconpresence">
                                    <ion-icon name="camera"></ion-icon>
                                </div>
                                <div class="presencedetail">
                                    <h4 class="presencetitle">Pulang</h4>
                                    <span>{{ $presensisekarang != null && $presensisekarang->jam_keluar != null ? $presensisekarang->jam_keluar : 'Belum Absen'}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
<!-- * App Capsule -->
