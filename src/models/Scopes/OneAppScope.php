<?php namespace Bootleg\Cms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ScopeInterface;

class OneAppScope implements ScopeInterface {

	private $model;

	protected $app_id_column = 'application_id';

	public function apply(Builder $builder, Model $model)
	{
		$builder->inApplication();
	}

	public function remove(Builder $builder, Model $model)
	{
		$query = $builder->getQuery();
		foreach( (array) $query->wheres as $key => $where) {
			if($where['column'] == $this->app_id_column){
				unset($query->wheres[$key]);

				$query->wheres = array_values($query->wheres);
				break;
			}
		}
	}
}