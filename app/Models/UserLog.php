<?php

namespace App\Models;

use App\Models\User;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

 /**
 * @OA\Schema(
 *     schema="UserLog",
 *     title="UserLog",
 *     description="User log model",
 *     @OA\Property(
 *         property="id",
 *         type="string",
 *         format="uuid",
 *         description="The unique uuid for the log entry"
 *     ),
 *     @OA\Property(
 *         property="action",
 *         type="string",
 *         description="The action performed by the user"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="text",
 *         description="The description of the action performed by the user"
 *     ),
 *     @OA\Property(
 *         property="userId",
 *         type="string",
 *         description="The UUID of the user who performed the action"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="timestamp",
 *         format="date-time",
 *         description="The date and time when the log entry was created"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="timestamp",
 *         format="date-time",
 *         description="The date and time when the log entry was last updated"
 *     ),
 * )
 */

class UserLog extends Model
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
        'action',
        'description',
        'userId'
    ];

    /**
     * Define a relationship between the UserLog model and the User model.
     * In this case, a UserLog belongs to a User.
     * The second parameter specifies the foreign key column name (userId) on the favourites table.
     */
    public function user(){
        return $this->belongsTo(User::class, 'userId');
    }
}
