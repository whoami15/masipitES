<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GradeLevel extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_grade_level';

    public function learning_material(){
        return $this->hasMany('App\LearningMaterial', 'user_id', 'id');
    }

}
