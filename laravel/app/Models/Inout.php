<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inout extends Model
{
    use HasFactory;
    protected $table = "checkinout";
    protected $fillable = ["id_outlet","id_user","check_in","latitude","longitude","fn_checkin","note_checkin"];
    public $timestamps = false;

    public function outlet() {
    	return $this->belongsTo(Outlets::class, 'id_outlet');
    }
}
