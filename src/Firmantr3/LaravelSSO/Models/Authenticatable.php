<?php

namespace Firmantr3\LaravelSSO\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Authenticatable
 * @package Firmantr3\LaravelSSO\Models
 * @version September 18, 2019, 11:02 am UTC
 *
 * @property \Firmantr3\LaravelSSO\Models\Credential credential
 * @property int credential_id
 * @property string remember_token
 */
class Authenticatable extends Model
{
    public $table = 'authenticatables';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'authenticatable_id',
        'authenticatable_type',
        'credential_id',
        'remember_token'
    ];

    protected $hidden = [
        'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function credential()
    {
        return $this->belongsTo(\Firmantr3\LaravelSSO\Models\Credential::class, 'credential_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function user()
    {
        return $this->morphTo('authenticatable');
    }
}
