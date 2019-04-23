<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LearningMaterial extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_learning_materials';

    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function subject_user(){
        return $this->belongsTo('App\Subject', 'subject', 'id');
    }

    public function grade_level_user(){
        return $this->belongsTo('App\GradeLevel', 'grade_level', 'id');
    }

}
