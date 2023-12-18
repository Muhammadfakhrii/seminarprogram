<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Karyawan;

class DashboardController extends Controller
{
    public function index()
    {
        $hariini = date("Y-m-d");
        $nik = Auth::guard('karyawan')->user()->nik;
        $presensisekarang = DB::table('presensis')->where('nik', $nik)->where('tgl_presensi', $hariini)->first();
        return view('dashboard.dashboard', compact('presensisekarang'));
    }

    public function dashboardadmin()
    {
        $totalKaryawan = Karyawan::count();

        $hariini = date("Y-m-d");

        // Get a list of employees who are late today
        $karyawanTelat = DB::table('presensis')
        ->join('karyawans', 'presensis.nik', '=', 'karyawans.nik')
        ->select('karyawans.nama', 'presensis.jam_masuk')
        ->whereDate('tgl_presensi', $hariini)
        ->whereTime('jam_masuk', '>', '08:00:00')
        ->get();

        $jumlahKaryawanHadir = DB::table('presensis')
            ->whereDate('tgl_presensi', today())
            ->distinct('nik')
            ->count();

        return view('dashboard.dashboardadmin', compact('totalKaryawan', 'jumlahKaryawanHadir', 'karyawanTelat'));
    }
}
