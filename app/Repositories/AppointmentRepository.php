<?php

namespace App\Repositories;

use App\Models\Appointment;
use App\Repositories\BaseRepository;

/**
 * Class AppointmentRepository
 * @package App\Repositories
 * @version November 29, 2020, 1:46 am UTC
*/

class AppointmentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Appointment::class;
    }
}
