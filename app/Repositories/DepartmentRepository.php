<?php

namespace App\Repositories;

use App\Models\Department;
use App\Repositories\BaseRepository;

/**
 * Class DepartmentRepository
 * @package App\Repositories
 * @version November 25, 2020, 8:48 pm UTC
*/

class DepartmentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'slug',
        'note',
        'active'
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
        return Department::class;
    }
}
