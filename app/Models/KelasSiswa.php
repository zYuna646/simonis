<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelasSiswa extends Model
{
    protected $fillable = ['kelas_id', 'user_id'];
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
