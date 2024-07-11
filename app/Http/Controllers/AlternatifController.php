<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Bobot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AlternatifController extends Controller
{
    public function index()
    {
        $alternatifs = Schema::getColumnListing('alternatif');
        $exclude = ['id','name','created_at','updated_at'];
        $alternatif = array_diff($alternatifs, $exclude);
        $data = Alternatif::all();
        $user_id = Auth::id();
        // Fetch the latest weights used by the user
        $Nilaibobots = Bobot::where('user_id', $user_id)->first();
        return view('alternatif', compact('data','alternatif', 'Nilaibobots'));
    }
    public function store(Request $request)
    {
        $alternatifs = Schema::getColumnListing('alternatif');
        $exclude = ['id', 'created_at', 'updated_at'];
        $columns = array_diff($alternatifs, $exclude);

        // Validate dynamically
        $rules = ['name' => 'required|string'];
        foreach ($columns as $column) {
            if ($column !== 'name') {
                $rules[$column] = 'required|numeric';
            }
        }
        
        $request->validate($rules);

        // Create dynamically
        $data = $request->only($columns);
        $data['name'] = $request->name; // Including name explicitly
        Alternatif::create($data);

        return redirect()->route('admin.alternatif')->with('success', 'Data berhasil ditambahkan');
    }
    public function update(Request $request, $id)
    {
        $alternatifs = Schema::getColumnListing('alternatif');
        $exclude = ['id', 'created_at', 'updated_at'];
        $columns = array_diff($alternatifs, $exclude);

        // Separate name validation from other columns
        $rules = ['name' => 'required|string'];
        foreach ($columns as $column) {
            if ($column !== 'name') {
                $rules[$column] = 'required|numeric';
            }
        }

        $request->validate($rules);

        // Update dynamically
        $alternatif = Alternatif::findOrFail($id);
        $data = $request->only($columns);
        $data['name'] = $request->name; // Including name explicitly
        $alternatif->update($data);

        return redirect()->route('admin.alternatif')->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $alternatif = Alternatif::findOrFail($id);
        $alternatif->delete();
        return redirect()->route('admin.alternatif')->with('success', 'Data berhasil dihapus');
    }
}
