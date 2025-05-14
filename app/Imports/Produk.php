<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Produk as ModelProduk;
use Maatwebsite\Excel\Concerns\WithStartRow;

class Produk implements ToCollection, WithStartRow
{
    public function startRow(): int {
        return 1;
    }
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $col) {
            $kodeyangadadidatabe = ModelProduk::where('kode', $col['1'])->firts();
            if(!$kodeyangadadidatabe) {
                $simpan = new ModelProduk();
                $simpan->kode = $col(1);
                $simpan->nama = $col(2);
                $simpan->harga = $col(3);
                $simpan->stock = $col(4);
                $simpan->save();

            }
        }
    }
}
