<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Bobot;
use App\Models\Kriteria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class HitungController extends Controller
{
    public function index()
    {
        $bobots = Schema::getColumnListing('bobots');
        $exclude = ['id', 'user_id', 'created_at', 'updated_at'];
        $bobot = array_diff($bobots, $exclude);
        $data = Bobot::with('user')->get();
        $user = User::all();
        $colKriteria = Schema::getColumnListing('alternatif');
        // Columns to exclude
        $exKriteria = ['id', 'name', 'created_at', 'updated_at'];
        // Filter columns
        $KriteriaSelect = array_values(array_diff($colKriteria, $exKriteria));
        $tipekriteria = Kriteria::whereIn('label', $KriteriaSelect)->pluck('jenis', 'label')->toArray();
        $user_id = Auth::id();
        // Fetch the latest weights used by the user
        $Nilaibobots = Bobot::where('user_id', $user_id)->first();
        return view('hitung', compact('data', 'bobot', 'user', 'tipekriteria', 'Nilaibobots'));
    }
    public function HitungSAW()
    {
        // $kriteria = Kriteria::where('label')->get();
        // dd($kriteria);
        $colKriteria = Schema::getColumnListing('alternatif');
        // Columns to exclude
        $exKriteria = ['id', 'name', 'created_at', 'updated_at'];
        // Filter columns
        $KriteriaSelect = array_values(array_diff($colKriteria, $exKriteria));
        // dd($KriteriaSelect);
        $user_id = Auth::id();

        // Fetch the latest weights used by the user
        $bobots = Bobot::where('user_id', $user_id)->latest()->first();
        if (!$bobots) {
            return redirect()->route('admin.Bobot')->with('error', 'Bobot not found.');
        }

        $BobotSelect = array_values(array_diff(Schema::getColumnListing('bobots'), ['id', 'user_id', 'created_at', 'updated_at']));

        // Filter bobotsArray sesuai dengan KriteriaSelect
        $bobotsArray = array_intersect_key($bobots->toArray(), array_flip($KriteriaSelect));
        // dd($bobotsArray);

        // Normalize the Bobot values
        $normalizedBobots = [];
        $totalSum = 0;

        // Calculate the total sum of all weights
        foreach ($BobotSelect as $column) {
            $totalSum += $bobotsArray[$column];
        }

        // Normalize each Bobot value
        foreach ($BobotSelect as $column) {
            $normalizedBobots[$column] = $bobotsArray[$column] / $totalSum;
        }
        // dd($normalizedBobots);
        session()->put('normalized_bobots', $normalizedBobots);

        // Fetch criteria types
        $tipekriteria = Kriteria::whereIn('label', $KriteriaSelect)->pluck('jenis', 'label')->toArray();
        // dd($tipekriteria);

        // Ambil nilai dari kolom yang sesuai dengan $KriteriaSelect dari tabel 'alternatif'
        $charList = Alternatif::select($KriteriaSelect)->get();

        // Hitung MAX untuk kriteria manfaat dan MIN untuk kriteria biaya
        $maxValues = [];
        $minValues = [];
        foreach ($tipekriteria as $kriteriaName => $kriteriaType) {
            $values = $charList->pluck($kriteriaName)->toArray();
            if ($kriteriaType === 'benefit') {
                $maxValues[$kriteriaName] = max($values);
            } elseif ($kriteriaType === 'cost') {
                $minValues[$kriteriaName] = min($values);
            }
        }
        $alternatif = Alternatif::all();

        // dd($maxValues, $minValues);

        session()->put('maxValues', $maxValues);
        session()->put('minValues', $minValues);

        foreach ($alternatif as $character) {
            $characterNormalized = [];
            foreach ($KriteriaSelect as $kriteriaName) {
                $kriteriaType = $tipekriteria[$kriteriaName];
                if ($kriteriaType === 'benefit') {
                    $characterNormalized[$kriteriaName] = $character->$kriteriaName / $maxValues[$kriteriaName];
                } elseif ($kriteriaType === 'cost') {
                    $characterNormalized[$kriteriaName] = $minValues[$kriteriaName] / $character->$kriteriaName;
                }
            }
            $normalizedValues[$character->id] = $characterNormalized;
        }

        session()->put('normalisasi_saw', $normalizedValues);

        $preferensiValues = [];
        foreach ($normalizedValues as $characterId => $characterNormalized) {
            $preferensi = 0;
            foreach ($characterNormalized as $kriteriaName => $normalizedValue) {
                $preferensi += $normalizedValue * $normalizedBobots[$kriteriaName];
            }
            $preferensiValues[$characterId] = $preferensi;
        }

        // Simpan nilai preferensi ke session (opsional)
        session()->put('preferensi_values', $preferensiValues);

        // Fetch the character information
        // Ambil data produk berdasarkan karakter ID yang sesuai
        $characterIds = array_keys($preferensiValues);
        $produk = Alternatif::whereIn('id', $characterIds)->get(['id', 'name']);
        $produkData = $produk->keyBy('id')->toArray();

        // Urutkan nilai preferensi dalam urutan menurun
        arsort($preferensiValues);

        // Rangking karakter berdasarkan nilai preferensi
        $rankedSAWData = [];
        $rankSAW = 1;
        foreach ($preferensiValues as $characterId => $nilaiSAW) {
            $rankedItemSAW = new \stdClass();
            $rankedItemSAW->rank_saw = $rankSAW++;
            $rankedItemSAW->nilai_preferensi_saw = $nilaiSAW;
            $rankedItemSAW->nama = $produkData[$characterId]['name'];
            $rankedItemSAW->normalisasi = $normalizedValues[$characterId];
            $rankedItemSAW->nilai_asli = $alternatif->where('id', $characterId)->first()->toArray();
            $rankedItemSAW->minmax = [
                'max' => $maxValues,
                'min' => $minValues
            ];
            $rankedSAWData[] = $rankedItemSAW;
        }

        // Simpan data yang telah di-rangking ke session (opsional)
        session()->put('ranked_data_saw', $rankedSAWData);

        // Tampilkan data yang telah di-rangking
        // dd($rankedSAWData);

        return redirect()->route('admin.Hitung')->with('success', 'Perhitungan SAW berhasil.');
    }
}
