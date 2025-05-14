<?php

namespace App\Livewire;

use App\Models\DetilTransaksi;
use App\Models\Transaksi as ModelsTransaksi;
use Livewire\Component;
use App\Models\Produk;

class Transaksi extends Component
{
    public $kode, $total,  $kembalian, $totalSemuaBelanja;
    public $bayar = 0;
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
    
    public function transaksiSelesai()
    {
        $this->transaksiAktif->status = 'selesai';
        $this->transaksiAktif->save();
        $this->reset();
        session()->flash('success', 'Transaksi berhasil di simpan.');
        return redirect()->to('/transaksi');
}

    public function hapusProduk($id) {
        $detil = DetilTransaksi::find($id);
        if($detil) {
            $produk = Produk::find($detil->produk_id);
            $produk->stock += $detil->jumlah;
            $produk->save();
        }
        $detil->delete();
        $this->reset('kode');
    }

    public function batalTransaksi() {
        if ($this->transaksiAktif) {
            $detilTransaksi = DetilTransaksi::where('transaksi_id', $this->transaksiAktif->id)->get(); 
            foreach ($detilTransaksi as $detil) {
            $produk = Produk::find($detil->produk_id);
            $produk->stock += $detil->jumlah;
            $produk->save();
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
            $produk->stock -= 1;
            $produk->save();
            $this->reset('kode');
        }
    }

    public function updatedBayar() {
        if($this->bayar > 0) {
            $this->kembalian = $this->bayar - $this->totalSemuaBelanja;
        }else {
            $this->kembalian = 0;
        }
    }
    // Untuk menampilkan data pada layar
    public function render()
    {
        if($this->transaksiAktif) {
            $semuaProduk = DetilTransaksi::where('transaksi_id', $this->transaksiAktif->id)->get();
            $this->totalSemuaBelanja = $semuaProduk->sum(function($detil) {
                return $detil->produk->harga * $detil->jumlah;
            });
        }else {
            $semuaProduk = [];
        }
        return view('livewire.transaksi')->with([
            'semuaProduk' => $semuaProduk
        ]);
    }
}
