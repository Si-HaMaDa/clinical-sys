<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Department",
 *      required={"name", "note"},
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="slug",
 *          description="slug",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="note",
 *          description="note",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="active",
 *          description="active",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class Department extends Model
{
    use SoftDeletes;

    public $table = 'departments';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'name',
        'note'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'slug' => 'string',
        'note' => 'string',
        'active' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'slug' => 'nullable',
        'note' => 'required',
        'active' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function branches()
    {
        return $this->belongsToMany(\App\Models\Branch::class);
    }
}
