<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimulasiGudang extends Model
{
    use HasFactory;

    protected $table = 'simulasi_gudang';

    protected $fillable = [
        'jumlah_barang',
        'jumlah_relawan',
        'kapasitas_sorting',
        'kapasitas_packaging',
        'status_bottleneck',
    ];
}
