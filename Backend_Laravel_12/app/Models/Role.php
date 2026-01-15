<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, HasFactory;

    protected $fillable = ['name'];
    protected $guarded = ['id'];
    public $timestamps = true;

    public function menus()
    {
        return $this->belongsToMany(Menu::class);
    }

}
