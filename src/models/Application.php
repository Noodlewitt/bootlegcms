<?php
use Baum\Node;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Node {

    use \Bootleg\Cms\Models\Traits\HasSettingModelTrait;

    use SoftDeletes;
    protected $dates = ['deleted_at'];


    protected $table = 'applications';
    protected $fillable = ['name', 'parent_id', 'cms_package', 'package'];
    protected $guarded = ['id', 'lft', 'rgt', 'depth'];

    protected $leftColumn = 'lft';
    protected $rightColumn = 'rgt';
    protected $depthColumn = 'depth';
    protected $parentColumn = 'parent_id';

    const DEFAULT_APPLICATION_ICON = 'http://files.madeinkatana.com.s3.amazonaws.com/images/madeinkatana.png';

    protected $_settings = null; //holds settings for this application item so we don't have to constantly query it.

    public static $rules = [
        'name'   => 'required|unique:applications',
        'domain' => 'required',
        //'parent_id' => 'required'
    ];

    public function creator()
    {
        return ($this->belongsTo('Bootleg\Cms\User', 'user_id'));
    }

    public function url()
    {
        return ($this->hasMany('ApplicationUrl'));
    }

    public function setting()
    {
        return ($this->hasMany('Applicationsetting'));
    }

    public function languages()
    {
        return ($this->hasMany('ApplicationLanguage'));
    }

    public function plugins()
    {
        return ($this->belongsToMany('Plugin'));
    }

    public function permission()
    {
        return $this->morphMany('Permission', 'controller');
    }

    public static function getApplication($domain = '', $folder = '')
    {

        return (unserialize($GLOBALS['application']));
    }
}
