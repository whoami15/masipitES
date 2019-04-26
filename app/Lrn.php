<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lrn extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_lrn';

    public function user(){
        return $this->belongsTo('App\User', 'used_by_user_id', 'id');
    }

}
