<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Appointment;

class AppointmentApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_appointment()
    {
        $appointment = factory(Appointment::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/backend/appointments', $appointment
        );

        $this->assertApiResponse($appointment);
    }

    /**
     * @test
     */
    public function test_read_appointment()
    {
        $appointment = factory(Appointment::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/backend/appointments/'.$appointment->id
        );

        $this->assertApiResponse($appointment->toArray());
    }

    /**
     * @test
     */
    public function test_update_appointment()
    {
        $appointment = factory(Appointment::class)->create();
        $editedAppointment = factory(Appointment::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/backend/appointments/'.$appointment->id,
            $editedAppointment
        );

        $this->assertApiResponse($editedAppointment);
    }

    /**
     * @test
     */
    public function test_delete_appointment()
    {
        $appointment = factory(Appointment::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/backend/appointments/'.$appointment->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/backend/appointments/'.$appointment->id
        );

        $this->response->assertStatus(404);
    }
}
