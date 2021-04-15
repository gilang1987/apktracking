<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlets extends Model
{
    use HasFactory;
    protected $table = "outlet";
    //protected $fillable = ["filename","id_user"];
    public $timestamps = false;

    public function inouts() {
    	return $this->hasMany(Inout::class,'id_outlet');
    }
}
