<?php 

namespace Firmantr3\LaravelSSO\Traits;

use Firmantr3\LaravelSSO\Models\Authenticatable;

trait UserTrait {

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function authenticatable()
    {
        return $this->morphOne(Authenticatable::class, 'authenticatable');
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->authenticatable->{$this->getRememberTokenName()};
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        return $this->authenticatable->update([
            $this->getRememberTokenName() => $value
        ]);
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
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
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    /**
     * @return \Firmantr3\LaravelSSO\Models\Credential|null
     */
    public function getCredentialAttribute()
    {
        return optional(optional($this->authenticatable)->credential);
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
