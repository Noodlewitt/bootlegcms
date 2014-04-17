<?php
class Contentsetting extends Eloquent {
    protected $fillable = array('content_id', 'name', 'value', 'field_type');

    protected $table = 'content_settings';
    
    public function content(){
        return($this->belongsTo('Content'));
    }
}