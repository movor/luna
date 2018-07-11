<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HashRecord extends Model
{
    protected $fillable = ['hash', 'model', 'record_id'];

    public $timestamps = false;

    public function getModelName()
    {
        $explodedNamespace = explode('\\', $this->model);
        // function "end" requires pointer to work
        return end($explodedNamespace);
    }

    public function getRecord()
    {
        return (new $this->model)->where('id', $this->record_id)->first();
    }

}