<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Appointment",
 *      required={"name", "email", "phone", "gender", "birth", "address", "doctor_id", "date", "time", "payment_method"},
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="email",
 *          description="email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="phone",
 *          description="phone",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="gender",
 *          description="gender",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="birth",
 *          description="birth",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="address",
 *          description="address",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="patient_id",
 *          description="patient_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="doctor_id",
 *          description="doctor_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="date",
 *          description="date",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="payment_method",
 *          description="payment_method",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="offer_id",
 *          description="offer_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="tax_id",
 *          description="tax_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="note",
 *          description="note",
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
class Appointment extends Model
{
    use SoftDeletes;

    public $table = 'appointments';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'birth',
        'address',
        'patient_id',
        'doctor_id',
        'date',
        'time',
        'payment_method',
        'offer_id',
        'tax_id',
        'note'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'gender' => 'string',
        'birth' => 'string',
        'address' => 'string',
        'patient_id' => 'integer',
        'doctor_id' => 'integer',
        'date' => 'date',
        'payment_method' => 'string',
        'offer_id' => 'integer',
        'tax_id' => 'integer',
        'note' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'required',
        'gender' => 'required',
        'birth' => 'required',
        'address' => 'required',
        'patient_id' => 'nullable',
        'doctor_id' => 'required',
        'date' => 'required',
        'time' => 'required',
        'payment_method' => 'required',
        'offer_id' => 'nullable',
        'tax_id' => 'nullable',
        'note' => 'nullable'
    ];
}
