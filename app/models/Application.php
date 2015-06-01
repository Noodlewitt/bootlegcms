<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;
class Application extends Baum\Node{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];


    protected $table = 'applications';
    protected $fillable = array('name', 'parent_id', 'cms_package', 'package');
    protected $guarded = array('id', 'lft', 'rgt', 'depth');

    protected $leftColumn = 'lft';
    protected $rightColumn = 'rgt';
    protected $depthColumn = 'depth';
    protected $parentColumn = 'parent_id';

    protected $_settings = NULL; //holds settings for this application item so we don't have to contantly query it.
    
    public static $rules = array(
		//'content' => 'required',
		//'parent_id' => 'required'
    );
    
    public function creator(){
        return($this->belongsTo('User', 'user_id'));
    }

    public function url(){
        return($this->hasMany('ApplicationUrl'));
    }
    
    public function setting(){
        return($this->hasMany('Applicationsetting'));
    }
    
    public function languages(){
        return($this->hasMany('ApplicationLanguage'));
    }

    public function plugins(){
        return($this->belongsToMany('Plugin'));
    }
    
    public function permission(){
        return $this->morphMany('Permission', 'controller');
    }
    
    public static function getApplication($domain='', $folder = ''){        
        return(unserialize($GLOBALS['application']));

    }
    

        /*
     * returns a single setting given the name;
     */
    public function getSetting($getSetting){
        $settings = $this->setting->filter(function($model) use(&$getSetting){
            return $model->name === $getSetting;
            
        });
        if($settings->count() == 0){
            return null;
        }
        if($settings->count() > 1){
            $return = array();
            foreach($settings as $setting){
                $return[] = $setting->value;
            }
        }
        else{
            $return = $settings->first()->value;
        }
        return($return);
    }
}
