<?php

namespace Firmantr3\LaravelSSO\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use Firmantr3\LaravelSSO\Models\User;
use Illuminate\Database\Eloquent\Model;
use Firmantr3\LaravelSSO\Exceptions\CredentialModelException;

/**
 * Class Credential
 * @package Firmantr3\LaravelSSO\Models
 * @version September 17, 2019, 11:01 am UTC
 *
 * @property string name
 * @property string email
 * @property string password
 */
class Credential extends Model
{

    public $table = 'credentials';

    protected $hidden = [
        'password',
    ];

    public $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function authenticatables()
    {
        return $this->hasMany(Authenticatable::class);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUserModel($query, string $model)
    {
        return $query->whereHas('authenticatables', function ($query) use ($model) {
            $query->where('authenticatable_type', $model);
        });
    }

    /**
     * Create authenticatable user from this credential
     *
     * @param array $attributes
     * @param string $model
     * @return User
     * @throws CredentialModelException
     */
    public function createAuthenticatableUser(string $model, array $attributes = [])
    {
        $authenticatableUser = new $model($attributes);

        return $this->attachUser($authenticatableUser);
    }

    /**
     * Attach a user model to this credential
     *
     * @param User $user
     * @return User
     */
    public function attachUser($user) {
        $userClass = get_class($user);
        if ($user instanceof User) {
            DB::beginTransaction();
            try {
                $user->save();
                $this->authenticatables()->create([
                    'authenticatable_id' => $user->id,
                    'authenticatable_type' => $userClass,
                ]);

                DB::commit();
            } catch (Exception $e) {
                DB::rollback();

                throw new CredentialModelException("Already have [{$userClass}] user.", 500);
            }

            $this->refresh();

            return $user;
        }
        
        throw new CredentialModelException("Model [{$userClass}] must be implement laravel's Authenticatable contract.", 500);
    }
}
