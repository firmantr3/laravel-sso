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
        return config('sso.credential');
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function credential() {
        /** @var \Illuminate\Database\Eloquent\Model $this */
        return $this->belongsTo($this->credentialClass(), $this->credentialKeyName(), 'id');
    }
    
    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return ! is_null($this->credential->email_verified_at);
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified()
    {
        return $this->credential->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
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
