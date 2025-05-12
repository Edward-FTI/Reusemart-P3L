<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class KategoriBarangController extends Controller
{

    public function index()
    {
        $kategori = KategoriBarang::all();
        if(count($kategori) > 0) {
            return response([
                'message' => 'Berhasil mengambil data kategori',
                'data' => $kategori
            ], 200);
        }
        return response([
            'message' => 'Data kategori kosong',
            'data' => []
        ], 400);
    }

    public function store(Request $request)
    {
        //
    }


    public function show(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
