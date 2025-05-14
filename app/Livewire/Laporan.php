<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaksi;

class Laporan extends Component
{
    public function render()
    {
        $semuaTransaksi = Transaksi::where('status', '!=', 'pending')
            ->where('status', '!=', 'batal')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('livewire.laporan')->with([
            'semuaTransaksi' => $semuaTransaksi,
        ]);
    }
}
