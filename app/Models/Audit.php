<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    //
    protected $table = 'audit_logs'; // Specify the correct table name

    protected $fillable = ['user_id', 'action', 'model_type', 'model_id', 'changes', 'old_values'];

    protected $casts = [
        'changes' => 'array',
        'old_values' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'users_area');
    }
}
