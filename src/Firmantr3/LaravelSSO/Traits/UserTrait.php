<?php 

namespace Firmantr3\LaravelSSO\Traits;

use Firmantr3\LaravelSSO\Models\Credential;

trait UserTrait {

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function credentialKeyName()
    {
        return 'credential_id';
    }

    /** @return mixed */
    public function credentialKey() {
        return $this->{$this->credentialKeyName()};
    }

    /** @return string */
    public function credentialClass() {
        return Credential::class;
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function credential() {
        /** @var \Illuminate\Database\Eloquent\Model $this */
        return $this->belongsTo($this->credentialClass(), $this->credentialKeyName(), 'id');
    }

    /**
     * @return string
     */
    public function getEmailAttribute()
    {
        return $this->credential->email;
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->credential->name;
    }

    /**
     * @return string
     */
    public function getPasswordAttribute()
    {
        return $this->credential->password;
    }
    
}
