<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublicMessage extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_public_messages';

    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

}
