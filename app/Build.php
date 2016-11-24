<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Build extends Model
{
    protected $table = 'builds';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'bundleIdentifier',
        'installFolder',
        'installFileName',
        'version',
        'buildNumber',
        'platform',
        'revision',
        'tag',
		'androidBundleVersionCode',
		'iphoneBundleVersion',
		'iphoneTitle',
        'notes'
    ];
	
	public function project()
	{
		return $this->belongsTo('App\Project');
	}
}
