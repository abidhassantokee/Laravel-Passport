<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'user_id',
    ];

    /**
     * Sets the user_id attribute.
     *
     * @param string $value
     * @return void
     */
    public function setUserIdAttribute($value)
    {
        $this->attributes['user_id'] = auth()->user()->is_admin ? $value : null;
    }
}
