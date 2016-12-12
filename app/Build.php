<?php

namespace App;

use Conner\Tagging\Taggable;
use Illuminate\Database\Eloquent\Model;

class Build extends Model
{
    use Taggable;

    protected $table = 'builds';

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
