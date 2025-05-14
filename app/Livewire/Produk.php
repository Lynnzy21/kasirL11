<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Produk as ModelProduk;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Produk as ImporProduk;

class Produk extends Component
{
    use WithFileUploads;
    public $pilihanMenu= 'lihat';
    public $nama;
    public $kode;
    public $harga;
    public $stock;
    public $produkTerpilih;
    public $fileExcel;

    public function importExcel() 
    {
        Excel::import(new ImporProduk, $this->fileExcel);
        $this->reset();
    } 


public function pilihEdit($id) 
{
    $this->produkTerpilih = ModelProduk::findOrFail($id);
        $this->nama = $this->produkTerpilih->nama;
        $this->kode = $this->produkTerpilih->kode;
        $this->harga = $this->produkTerpilih->harga;
        $this->stock = $this->produkTerpilih->stock;        
        $this->pilihanMenu = 'edit';
}

public function simpanEdit() 
{
    $this->validate([
            'nama' => 'required',
            'kode' => 'required|unique:produks,kode,' . $this->produkTerpilih->id,
            'harga' => 'required',
            'stock' => 'required',
        ],[
            'nama.required' => 'Nama Harus Diisi',
            'kode.required' => 'kode harus Diisi', 
            'kode.unique' => 'kode Telah DiPilih',
            'harga.required' => 'Harga  Harus Diisi', 
            'stock.required' => 'stock Harus Diisi',
        ]);
        $simpan = $this->produkTerpilih;
        $simpan->nama = $this->nama;
        $simpan->kode = $this->kode;
        $simpan->harga = $this->harga;
        $simpan->stock = $this->stock;
        $simpan->save();

        $this->reset();
        $this->pilihanMenu = 'lihat';
}


public function pilihHapus($id) {
        $this->produkTerpilih = ModelProduk::findOrFail($id);
        $this->pilihanMenu = 'hapus';
    }


    public function hapus() {
        $this->produkTerpilih->delete();
        $this->reset();
    }


    public function simpan() {
        $this->validate([
            'nama' => 'required',
            'kode' => 'required|unique:produks,kode',
            'harga' => 'required',
            'stock' => 'required',
        ],[
            'nama.required' => 'Nama Harus Diisi',
            'kode.required' => 'kode harus Diisi', 
            'kode.unique' => 'kode Telah DiPilih',
            'harga.required' => 'Harga  Harus Diisi', 
            'stock.required' => 'stock Harus Diisi',
        ]);
        $simpan = new ModelProduk();
        $simpan->nama = $this->nama;
        $simpan->kode = $this->kode;
        $simpan->harga = $this->harga;
        $simpan->stock = $this->stock;
        $simpan->save();

        $this->reset(['nama','kode','harga','stock']);
        $this->pilihanMenu = 'lihat';
    }


    public function pilihMenu($menu) {
        $this->pilihanMenu = $menu;
    }


    public function render()
    {
        return view('livewire.produk')->with([
            'semuaProduk' => ModelProduk::all()
        ]);
    }
}
