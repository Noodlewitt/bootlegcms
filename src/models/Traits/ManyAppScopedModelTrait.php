<?php namespace Bootleg\Cms;

use App;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Bootleg\Cms\ManyAppScope;

trait ManyAppScopedModelTrait
{
	 public static function bootManyAppScopedModelTrait()
     {
        $ManyAppScope = App::make('Bootleg\Cms\ManyAppScope');

        // Add Global scope that will handle all operations except create()
        static::addGlobalScope($ManyAppScope);
    }

    public static function allItems()
    {
        return with(new static())->newQueryWithoutScope(new ManyAppScope());
    }
    public function scopeInApplication($q){
        return $q->whereHas('applications', function($sq){
            $sq->where('application_id',\Application::getApplication()->application_id);
        });
    }
}