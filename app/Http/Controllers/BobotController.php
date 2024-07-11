<?php

namespace App\Http\Controllers;

use App\Models\Bobot;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class BobotController extends Controller
{
    public function index()
    {
        $bobots = Schema::getColumnListing('bobots');
        $exclude = ['id', 'user_id', 'created_at', 'updated_at'];
        $bobot = array_diff($bobots, $exclude);
        $data = Bobot::with('user')->get();
        $user = User::all();
        return view('bobot', compact('data', 'bobot', 'user'));
    }
    public function store(Request $request)
    {
        $bobots = Schema::getColumnListing('bobots');
        $exclude = ['id', 'user_id', 'created_at', 'updated_at'];
        $columns = array_diff($bobots, $exclude);

        // Validate dynamically
        $rules = ['user_id' => 'required'];
        foreach ($columns as $column) {
            if ($column !== 'name') {
                $rules[$column] = 'required|numeric';
            }
        }

        $request->validate($rules);

        // Create dynamically
        $data = $request->only($columns);
        $data['user_id'] = $request->user_id; // Save user_id from select input
        $data['created_at'] = now();
        Bobot::create($data);

        return redirect()->route('admin.Bobot')->with('success', 'Data berhasil ditambahkan');
    }
    public function update(Request $request, $id)
    {
        $bobots = Schema::getColumnListing('bobots');
        $exclude = ['id', 'created_at', 'updated_at'];
        $columns = array_diff($bobots, $exclude);

        // Validate dynamically
        $rules = ['user_id' => 'required|exists:users,id'];
        foreach ($columns as $column) {
            if ($column !== 'user_id') {
                $rules[$column] = 'required|numeric';
            }
        }
        // dd($rules);
        $request->validate($rules);

        // Update dynamically
        $data = $request->only($columns);
        $bobot = Bobot::findOrFail($id);
        $bobot->update($data);

        return redirect()->route('admin.Bobot')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        $bobots = Bobot::findOrFail($id);
        $bobots->delete();
        return redirect()->route('admin.Bobot')->with('success', 'Data berhasil dihapus');
    }
}
