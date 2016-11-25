<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';

    /**
    * @var array
    */
    protected $fillable = [
        'name'
    ];
    
    public function builds()
    {
        return $this->belongsToMany('App\Build', 'build_tag');
    }
}
