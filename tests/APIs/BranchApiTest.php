<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Branch;

class BranchApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_branch()
    {
        $branch = factory(Branch::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/branches', $branch
        );

        $this->assertApiResponse($branch);
    }

    /**
     * @test
     */
    public function test_read_branch()
    {
        $branch = factory(Branch::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/branches/'.$branch->id
        );

        $this->assertApiResponse($branch->toArray());
    }

    /**
     * @test
     */
    public function test_update_branch()
    {
        $branch = factory(Branch::class)->create();
        $editedBranch = factory(Branch::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/branches/'.$branch->id,
            $editedBranch
        );

        $this->assertApiResponse($editedBranch);
    }

    /**
     * @test
     */
    public function test_delete_branch()
    {
        $branch = factory(Branch::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/branches/'.$branch->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/branches/'.$branch->id
        );

        $this->response->assertStatus(404);
    }
}
