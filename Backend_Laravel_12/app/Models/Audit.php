<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array'
    ];

    public static function boot(){
        parent::boot();

        // create a event to happen on creating
        self::creating(function($model){
            $model->old_values   =  $model->old_values??json_encode([]);
            $model->new_values   =  $model->new_values??json_encode([]);
        });
    }

    public function auditable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

