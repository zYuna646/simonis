<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilais';
    
    protected $fillable = [
        'user_id',
        'kelas_id',
        'jenis',
        'tanggal',
        'nilai',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nilai' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}