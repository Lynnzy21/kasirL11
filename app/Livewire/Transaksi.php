<?php

namespace App\Livewire;

use App\Models\DetilTransaksi;
use App\Models\Transaksi as ModelsTransaksi;
use Livewire\Component;
use App\Models\Produk;

class Transaksi extends Component
{
    public $kode, $total, $bayar, $kembalian, $totalSemuaBelanja;
    public $transaksiAktif;

    public function transaksiBaru()
    {
        $this->reset();
        $this->transaksiAktif = new ModelsTransaksi();
        $this->transaksiAktif->kode = 'INV/' . date('YmdHis');
        $this->transaksiAktif->total  = 0;
        $this->transaksiAktif->status = 'pending';
        $this->transaksiAktif->save();
    }

    public function batalTransaksi() {
        if ($this->transaksiAktif) {
            $detilTransaksi = DetilTransaksi::where('transaksi_id', $this->transaksiAktif->id)->get(); 
            foreach ($detilTransaksi as $detil) {
                $detil->delete();
            }
            $this->transaksiAktif->delete();
        }
        $this->reset();
    }

    // Mencari produk kemudain di masukan pada transaksi punya kita
    public function updatedKode()
    {
        if(!$this->transaksiAktif || !$this->transaksiAktif->id) {
            session()->flash('error', 'Transaksi belum di buat. klik "Transaksi Baru" dulu.');
            return;
        }
        $produk = Produk::where('kode', $this->kode)->first();
            if($produk && $produk->stock > 0){
            $detil= DetilTransaksi::firstOrNew([
                'transaksi_id' => $this->transaksiAktif->id,
                'produk_id' => $produk->id,
            ],[
                'jumlah' => 0,
                
            ]);
            $detil->jumlah += 1;
            $detil->save();
            $this->reset('kode');
        }
    }

    // Untuk menampilkan data pada layar
    public function render()
    {
        if($this->transaksiAktif) {
            $semuaProduk = DetilTransaksi::where('transaksi_id', $this->transaksiAktif->id)->get();
        }else {
            $semuaProduk = [];
        }
        return view('livewire.transaksi')->with([
            'semuaProduk' => $semuaProduk
        ]);
    }
}
