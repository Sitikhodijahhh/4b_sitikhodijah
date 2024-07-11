<?php

namespace App\Http\Controllers;

use App\Models\Bobot;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KriteriasController extends Controller
{
    public function index()
    {
        $data = Kriteria::all();
        $user_id = Auth::id();
        // Fetch the latest weights used by the user
        $Nilaibobots = Bobot::where('user_id', $user_id)->first();
        return view('kriteria', compact('data', 'Nilaibobots'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'label' => 'required',
            'jenis' => 'required',
        ]);

        Kriteria::create($request->all());
        return redirect()->route('admin.kriteria')->with('success', 'Data berhasil ditambahkan');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'label' => 'required',
            'jenis' => 'required',
        ]);

        $kriteria = Kriteria::findOrFail($id);
        $kriteria->update($request->all());
        return redirect()->route('admin.kriteria')->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        $kriteria->delete();
        return redirect()->route('admin.kriteria')->with('success', 'Data berhasil dihapus');
    }
}
