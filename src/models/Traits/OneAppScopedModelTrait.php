<?php namespace Bootleg\Cms;

use App;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Bootleg\Cms\OneAppScope;

trait OneAppScopedModelTrait
{
	 public static function bootOneAppScopedModelTrait()
     {
        $OneAppScope = App::make('Bootleg\Cms\OneAppScope');

        // Add Global scope that will handle all operations except create()
        static::addGlobalScope($OneAppScope);
    }

    public static function allItems()
    {
        return with(new static())->newQueryWithoutScope(new OneAppScope());
    }
    public function scopeInApplication($q){
        return $q->where('application_id',\Application::getApplication()->application_id);
    }
}