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

    protected $table = 'credentials';

    protected $hidden = [
        'password',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

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
                $user->{$user->credentialKeyName()} = $this->id;

                if($user->isDirty()) {
                    $user->save();
                }

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
