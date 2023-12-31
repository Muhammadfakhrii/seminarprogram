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

//Menampilkan halaman presensi.
    public function create()
    {
        $hariini = date('Y-m-d');
        $nik = Auth::guard('karyawan')->user()->nik;
        $cek = DB::table('presensis')->where('tgl_presensi', $hariini)->where('nik', $nik)->count();
        return view('presensi.create', compact('cek'));
    }

//Menyimpan data presensi (masuk atau pulang) ke database.
public function store(Request $request)
{
    // Mendapatkan koordinat presensi dari request
    $latitude = $request->latitude;
    $longitude = $request->longitude;

    // Koordinat yang diizinkan
    $allowedLatitude = -7.004779773163583;
    $allowedLongitude = 107.57826546009974;

    // Menghitung jarak antara koordinat presensi dengan koordinat yang diizinkan
    $distance = $this->calculateDistance($latitude, $longitude, $allowedLatitude, $allowedLongitude);

    // Jarak maksimum yang diizinkan (contoh: 0.001 derajat, sesuaikan dengan kebutuhan)
    $maxAllowedDistance = 0.2; //saya memberi toleransi jarak dari titik koordinat sejauh 200meter

    // Pemeriksaan apakah presensi dilakukan di lokasi yang diizinkan
    if ($distance <= $maxAllowedDistance) {
        // Lanjutkan dengan proses presensi

        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_presensi = date("Y-m-d");
        $foto_masuk = date("H:i:s");
        $foto_keluar = date("H:i:s");
        $foto_masuk = $request->image;
        $img = $request->image;
        $folderPath = "uploads/";
        $image_parts = explode("base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = uniqid() . '.png';
        $file = $folderPath . $fileName;

        // Cek apakah data presensi sudah ada untuk hari ini
        $existingPresensi = DB::table('presensis')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->first();

        if ($existingPresensi) {
            // Jika sudah ada data presensi untuk hari ini
            if ($existingPresensi->jam_keluar == null) {
                // Karyawan belum melakukan absen pulang, jadi lakukan update data pulang
                $data_pulang = [
                    'jam_keluar' => date("H:i:s"),
                    'foto_keluar' => $fileName
                ];
                $update = DB::table('presensis')->where('id', $existingPresensi->id)->update($data_pulang);

                if ($update) {
                    Storage::put($file, $image_base64);
                    return redirect('/dashboard');
                } else {
                    // jika gagal mengupdate data pulang
                    return redirect('/')->with('error', 'Gagal mengupdate data pulang.');
                }
            } else {
                // Jika sudah ada data absen pulang, karyawan tidak dapat melakukan absen lagi
                return redirect('/')->with('error', 'Anda telah melakukan absen masuk dan pulang hari ini.');
            }
        } else {
            // Jika belum ada data presensi untuk hari ini, masukkan data absen masuk
            $data_masuk = [
                'nik' => Auth::guard('karyawan')->user()->nik,
                'tgl_presensi' => date("Y-m-d"),
                'jam_masuk' => date("H:i:s"),
                'foto_masuk' => $fileName
            ];
            $insert = DB::table('presensis')->insert($data_masuk);

            if ($insert) {
                Storage::put($file, $image_base64);
                return redirect('/dashboard');
            } else {
                // jika gagal memasukkan data masuk
                return redirect('/')->with('error', 'Gagal memasukkan data masuk.');
            }
        }

    } else {
        // Presensi dilakukan di lokasi yang tidak diizinkan
        return redirect('/')->with('error', 'Anda tidak dapat melakukan presensi di lokasi ini.');
    }
}

// Fungsi untuk menghitung jarak antara dua koordinat menggunakan formula Haversine
private function calculateDistance($lat1, $lon1, $lat2, $lon2)
{
    $earthRadius = 0.001; // Radius bumi dalam kilometer

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    $distance = $earthRadius * $c;

    return $distance;
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
