<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    //

    public function index() {
        return view('barang.index');
    }

    public function get_data()
    {
        $data = Barang::orderBy('id', 'desc')->get();
        return response()->json($data);
    }
    public function store_barang()
    {
        $id_edit = request('id_edit');
        if ($id_edit != "null") {
            $data = Barang::find($id_edit);
            $data->nama = request('nama');
            $data->jumlah = request('jumlah');
            $data->save();
        } else {
            $data = new Barang;
            $data->nama = request('nama');
            $data->jumlah = request('jumlah');
            $data->save();
        }
        return response()->json('sukses', 200);
    }
    public function get_detail($id)
    {
        $dt = Barang::find($id);
        return response()->json($dt, 200);
    }  
    public function hapus_barang($id)
    {
        Barang::where('id', $id)->delete();
        return response()->json('sukses', 200);
    }
    public function get_barang_paging() 
    {
        $paging = request('paging');
        $search = request('search');
        $data = Barang::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%$search%")->orWhere('jumlah', 'like', "%$search%");;
        })
        ->select(['id', 'nama', 'jumlah', 'created_at', 'updated_at'])->orderBy('nama')->paginate($paging);
        return response()->json($data);
    }
    
}