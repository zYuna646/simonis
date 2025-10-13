<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'tingkat',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function siswa()
    {
        return $this->belongsToMany(User::class, 'kelas_siswas', 'kelas_id', 'user_id');
    }
    
    public function totalSiswa()
    {
        return $this->siswa()->count();
    }
}
