<?php

namespace App\Http\Controllers;

use App\Models\Bobot;
use App\Models\User;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class HomeController extends Controller
{
    public function dashboard()
    {
        $user_id = Auth::id();
        // Fetch the latest weights used by the user
        $Nilaibobots = Bobot::where('user_id', $user_id)->first();
        return view('dashboard', compact('Nilaibobots'));
    }
    public function index()
    {

        $data = User::get();
        $user_id = Auth::id();
        // Fetch the latest weights used by the user
        $Nilaibobots = Bobot::where('user_id', $user_id)->first();
        return view('index', compact('data', 'Nilaibobots'));
    }

    public function create()
    {
        $user_id = Auth::id();
        // Fetch the latest weights used by the user
        $Nilaibobots = Bobot::where('user_id', $user_id)->first();
        return view('create', compact('Nilaibobots'));
    }
    public function store(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'email' => 'required|email',
            'nama' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data['email']  = $request->email;
        $data['name']  = $request->nama;
        $data['password']  = Hash::make($request->password);

        User::create($data);

        return redirect()->route('admin.index');
    }

    public function edit(Request $request, $id)
    {
        $data = User::find($id);
        $user_id = Auth::id();
        // Fetch the latest weights used by the user
        $Nilaibobots = Bobot::where('user_id', $user_id)->first();
        return view('edit', compact('data', 'Nilaibobots'));
    }
    public function update(Request $request, $id)
    {
        $validator = FacadesValidator::make($request->all(), [
            'email'     => 'required|email',
            'nama'      => 'required',
            'password'  => 'nullable',
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data['email']   = $request->email;
        $data['name']    = $request->nama;

        if ($request->password) {
            $data['password']   = Hash::make($request->password);
        }

        User::whereId($id)->update($data);

        return redirect()->route('admin.index');
    }

    public function delete(Request $request, $id)
    {
        $data = User::find($id);

        if ($data) {
            $data->delete();
        }

        return redirect()->route('admin.index');
    }
}
