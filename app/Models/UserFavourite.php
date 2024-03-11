<?php

namespace App\Models;

use App\Models\User;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @OA\Schema(
 *     schema="UserFavourite",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="string",
 *         format="uuid",
 *         readOnly="true"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="url",
 *         type="text"
 *     ),
 *     @OA\Property(
 *         property="author",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="text"
 *     ),
 *     @OA\Property(
 *         property="imageUrl",
 *         type="text"
 *     ),
 *     @OA\Property(
 *         property="userId",
 *         type="string",
 *         format="uuid"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="timestamp",
 *         format="date-time",
 *         readOnly="true"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="timestamp",
 *         format="date-time",
 *         readOnly="true"
 *     ),
 *     @OA\Property(
 *         property="user",
 *         ref="#/components/schemas/User"
 *     )
 * )
 */
class UserFavourite extends Model
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
        'title',
        'url',
        'author',
        'description',
        'imageUrl',
        'userId'
    ];

    /**
     * Define a relationship between the Favourite model and the User model.
     * In this case, a Favourite belongs to a User.
     * The second parameter specifies the foreign key column name (userId) on the favourites table.
     */
    public function user(){
        return $this->belongsTo(User::class, 'userId');
    }
}
