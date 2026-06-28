<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramDonasi extends Model
{
    use HasFactory;

    protected $table = 'program_donasi';

    protected $fillable = [
        'nama_program',
        'deskripsi',
        'target_dana',
        'dana_terkumpul',
        'status',
        'foto',
        'tanggal_berakhir',
    ];

    protected $casts = [
        'tanggal_berakhir' => 'date',
    ];

    public function donasis()
    {
        return $this->hasMany(Donasi::class, 'program_id');
    }

    public function getDaysRemaining(): ?int
    {
        if (!$this->tanggal_berakhir) {
            return null;
        }
        return now()->diffInDays($this->tanggal_berakhir, false);
    }

    public function isActive(): bool
    {
        if ($this->status !== 'aktif') {
            return false;
        }
        if ($this->tanggal_berakhir && now()->isAfter($this->tanggal_berakhir)) {
            return false;
        }
        return true;
    }
}
