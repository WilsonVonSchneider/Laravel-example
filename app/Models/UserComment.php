<?php

namespace App\Models;

use App\Models\User;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @OA\Schema(
 *     schema="UserComment",
 *     type="object",
 *     @OA\Property(
 *         property="uuid",
 *         type="string",
 *         readOnly=true,
 *         example="f6ebff50-aa5d-11eb-aebf-0242ac130002"
 *     ),
 *     @OA\Property(
 *         property="userUuid",
 *         type="string",
 *         example="f6ebff50-aa5d-11eb-aebf-0242ac130002"
 *     ),
 *     @OA\Property(
 *         property="url",
 *         type="text",
 *         example="https://example.com/article"
 *     ),
 *     @OA\Property(
 *         property="commentText",
 *         type="text",
 *         example="This is a comment"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="timestamp",
 *         format="date-time",
 *         readOnly=true,
 *         example="2023-05-08T12:30:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="timestamp",
 *         format="date-time",
 *         readOnly=true,
 *         example="2023-05-08T12:30:00Z"
 *     ),
 *     @OA\Property(
 *         property="user",
 *         ref="#/components/schemas/User"
 *)
 * )
 */
class UserComment extends Model
{

    use HasFactory;

    /**
     * Using UUID trait for unique UUID indetifiers (app/Traits/UUID.php)
     */
    use UUID;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'userId',
        'url',
        'commentText',
    ];

    /**
     * Define a relationship between the Favourite model and the User model.
     * In this case, a Favourite belongs to a User.
     * The second parameter specifies the foreign key column name (userId) on the favourites table.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
