<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_subjects';

    public function learning_material(){
        return $this->hasMany('App\LearningMaterial', 'user_id', 'id');
    }

}
