<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\UUID;
use App\Models\UserLog;
use App\Models\UserComment;
use App\Models\UserFavourite;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

    /**
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="User model",
 *     @OA\Property(
 *         property="id",
 *         description="User ID",
 *         type="string",
 *         format="uuid",
 *         readOnly=true
 *     ),
 *     @OA\Property(
 *         property="name",
 *         description="User name",
 *         type="string",
 *     ),
 *     @OA\Property(
 *         property="email",
 *         description="User email",
 *         type="string",
 *     ),
 *     @OA\Property(
 *         property="country",
 *         description="User country",
 *         type="string",
 *     ),
 *     @OA\Property(
 *         property="language",
 *         description="User language",
 *         type="string",
 *     ),
 *     @OA\Property(
 *         property="category",
 *         description="User category",
 *         type="string",
 *     ),
 *     @OA\Property(
 *         property="role",
 *         description="User role",
 *         type="boolean",
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         description="Date and time of user creation",
 *         type="timestamp",
 *         format="date-time",
 *         readOnly=true
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         description="Date and time of user update",
 *         type="timestamp",
 *         format="date-time",
 *         readOnly=true
 *     ),
 *     @OA\Property(
 *         property="favourites",
 *         description="User's favourite news articles",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/UserFavourite")
 *     ),
 *     @OA\Property(
 *         property="comments",
 *         description="User's comments on news articles",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/UserComment")
 *     ),
 *     @OA\Property(
 *         property="logs",
 *         description="User's logs of activity on the news portal",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/UserLog")
 *     ),
 * )
 */

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Using UUID trait for unique UUID indetifiers (app/Traits/UUID.php)
     */
    use UUID;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'country',
        'language',
        'category',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Define a relationship between the User model and the UserFavourite model.
     * In this case, a User has many Favourites.
     * The second parameter specifies the foreign key column name (userId) on the favourites table.
     */
    public function favourites(){
        return $this->hasMany(UserFavourite::class, 'userId');
    }

        /**
     * Define a relationship between the User model and the UserComment model.
     * In this case, a User has many Comments.
     * The second parameter specifies the foreign key column name (userId) on the comments table.
     */
    public function comments(){
        return $this->hasMany(UserComment::class, 'userId');
    }

            /**
     * Define a relationship between the User model and the UserLog model.
     * In this case, a User has many Logs.
     * The second parameter specifies the foreign key column name (userId) on the comments table.
     */
    public function logs(){
        return $this->hasMany(UserLog::class, 'userId');
    }
}
