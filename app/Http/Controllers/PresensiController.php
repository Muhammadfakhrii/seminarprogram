<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Presensi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

use function PHPUnit\Framework\returnSelf;

class PresensiController extends Controller
{
    // Menampilkan halaman presensi.
    public function create()
    {
        $hariini = date('Y-m-d');
        $nik = Auth::guard('karyawan')->user()->nik;
        $cek = DB::table('presensis')->where('tgl_presensi', $hariini)->where('nik', $nik)->count();
        return view('presensi.create', compact('cek'));
    }

    // Menyimpan data presensi (masuk atau pulang) ke database.
    public function store(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $latitudekantor  = -6.950286216542213;
        $longitudekantor = 107.62459901857964;
        $lokasi = $request->lokasi;
        $lokasiuser = explode(",", $lokasi);
        $latitudeuser = $lokasiuser[0];
        $longitudeuser = $lokasiuser[1];
        $jarak = $this->distance($latitudekantor,$longitudekantor, $latitudeuser,$longitudeuser);
        $radius = round($jarak["meters"]);

        $foto_masuk = date("H:i:s");
        $foto_keluar = date("H:i:s");
        $image = $request->image;
        $folderPath = "uploads/";
        $image_parts = explode("base64,", $image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = uniqid() . '.png';
        $file = $folderPath . $fileName;

        $cek = DB::table('presensis')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->count();
        if ($cek > 0) {
            echo "error|Anda Sudah Melakukan Presensi Hari Ini|";
        } elseif ($radius > 50) {
            echo "error|Anda Diluar Radius Kantor|";
        } else {
        if ($cek > 0) {
            $data_pulang = [
                'jam_keluar' => $jam,
                'foto_masuk' => $foto_keluar
            ];

            $update = DB::table('presensis')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->update($data_pulang);

            if ($update) {
                echo "success|Anda Berhasil Presensi Pulang|out";
                Storage::put($file, $image_base64);
            } else {
                echo "error|Anda Gagal Melakukan Presensi Pulang|out";
            }
        } else {
            $data = [
                'nik' => $nik,
                'tgl_presensi' => $tgl_presensi,
                'jam_masuk' => $jam,
                'foto_masuk' => $foto_masuk
            ];

            $simpan = DB::table('presensis')->insert($data);

            if ($simpan) {
                echo "success|Anda Berhasil Presensi Masuk|in";
                Storage::put($file, $image_base64);
            } else {
                echo "error|Anda Gagal Melakukan Presensi Masuk|in";
                }
            }
        }
    }

        //Menghitung Jarak
        function distance($lat1, $lon1, $lat2, $lon2)
        {
            $theta = $lon1 - $lon2;
            $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
            $miles = acos($miles);
            $miles = rad2deg($miles);
            $miles = $miles * 60 * 1.1515;
            $feet = $miles * 5280;
            $yards = $feet / 3;
            $kilometers = $miles * 1.609344;
            $meters = $kilometers * 1000;
            return compact('meters');
        }

    //edit profile
    public function edit(){
    $nik = Auth::guard('karyawan')->user()->nik;
    $karyawan = DB::table('karyawans')->where('nik', $nik)->first();


    return view('presensi.edit' ,compact('karyawan'));
    }

    public function updateprofile(Request $request, $nik){
    $nama = $request->input('nama');
    $password = $request->password;

    // Memeriksa apakah $nama memiliki nilai yang valid
    if ($nama !== null) {
        $data = [
            'nama' => $nama,
        ];

        // memeriksa apakah password dikirim dalam permintaan
        if (!empty($password)) {
            $data['password'] = Hash::make($password);
        }

        // Memeriksa apakah ada file foto yang dikirim
        if ($request->hasFile('foto')) {
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
            $data['foto'] = $foto;
            $folderPath = public_path('assets/foto/karyawan');
            $request->file('foto')->move($folderPath, $foto);
        } else {
            $karyawan = DB::table('karyawans')->where('nik', $nik)->first();
            $data['foto'] = $karyawan->foto;
        }

        $update = DB::table('karyawans')->where('nik', $nik)->update($data);

        if ($update) {
            Session::flash('success', 'Data berhasil diubah');
            return redirect()->back();
        } else {
            Session::flash('error', 'Data gagal diubah');
            return redirect()->back();
        }
    } else {
        }
    }

    // membuat histori presensi
    public function history(){
        $now = now();
        $years = [$now->year - 1, $now->year, $now->year + 1];

        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                      "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        return view('presensi.history', compact('namabulan', 'years'));
    }

    public function gethistory(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nik = Auth::guard('karyawan')->user()->nik;

        $history = DB::table('presensis')
            ->whereRaw('MONTH(tgl_presensi)="'.$bulan.'"')
            ->whereRaw('YEAR(tgl_presensi)="'.$tahun.'"')
            ->where('nik', $nik)
            ->orderBy('tgl_presensi')
            ->get();

        return view('presensi.gethistory', compact('history'));
    }
}
