<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MataPelajaran extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    
    protected $fillable = [
        'name',
        'desc',
        'guru_id',
    ];

    /**
     * Get the guru that owns the mata pelajaran.
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}
