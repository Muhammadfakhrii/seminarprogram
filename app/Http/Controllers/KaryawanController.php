<?php

namespace App\Http\Controllers;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class KaryawanController extends Controller
{
    public function index(Request $request) {
        // Search nama karyawan
        $query = Karyawan::query();
        $query->select('karyawans.*');
        $query->orderBy('nama');

        if (!empty($request->nama_karyawan)) {
            $query->where('nama', 'like', '%' . $request->nama_karyawan . '%');
        }
        $karyawan = $query->paginate(10);

        return view('karyawan.index', compact('karyawan'));
    }

    public function store(Request $request){
        // Validation
        $validatedData = $request->validate([
            'nik' => 'required|unique:karyawans,nik',
            'nama' => 'required',
            'jabatan' => 'required',
        ]);

        // Check if the 'nik' already exists in the database
        $existingKaryawan = Karyawan::where('nik', $validatedData['nik'])->first();
        if ($existingKaryawan) {
            return redirect('/karyawan')->with('error', 'NIK sudah ada dalam database.');
        }
        // If 'nik' is unique, proceed to create the karyawan
        // $validatedData['password'] = bcrypt('default_password');
        $karyawan = new Karyawan($validatedData);

        // default password
        $karyawan->password = Hash::make('12345');

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $folderPath = public_path('assets/foto/karyawan');
            $fotoName = $validatedData['nik'] . '.' . $foto->getClientOriginalExtension();
            $foto->move($folderPath, $fotoName);
            $karyawan->foto = 'assets/foto/karyawan/' . $fotoName;
        }

        $karyawan->save();

        return redirect('/karyawan')->with('success', 'Data karyawan berhasil disimpan.');
    }

    public function edit(Request $request){
        $nik = $request->nik;
        $karyawan = DB::table('karyawans')->where('nik', $nik)->first();
        if (!$karyawan) {
        }
        return view('karyawan.edit', compact('karyawan'));
    }

    public function update($nik, Request $request){

        $validatedData = $request->validate([
            'nama' => 'required',
            'jabatan' => 'required',
            'foto' => 'image',
        ]);

        unset($validatedData['nik']);

        $update = DB::table('karyawans')->where('nik', $nik)->update($validatedData);

        return redirect('/karyawan')->with('success', 'Data karyawan berhasil diupdate.');
    }

    public function destroy($nik)
    {
        Karyawan::where('nik', $nik)->delete();
        return redirect('/karyawan')->with('success', 'Data karyawan berhasil dihapus.');
    }
}
