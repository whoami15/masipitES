<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SecurityKeys extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_security_keys';

    public function user(){
        return $this->belongsTo('App\User', 'used_by_user_id', 'id');
    }

}
