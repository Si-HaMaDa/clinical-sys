<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateBranchAPIRequest;
use App\Http\Requests\API\UpdateBranchAPIRequest;
use App\Models\Branch;
use App\Repositories\BranchRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\BranchResource;
use Response;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class BranchController
 * @package App\Http\Controllers\API
 */

class BranchAPIController extends AppBaseController
{
    /** @var  BranchRepository */
    private $branchRepository;

    public function __construct(BranchRepository $branchRepo)
    {
        $this->branchRepository = $branchRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/branches",
     *      summary="Get a listing of the Branches.",
     *      tags={"Branch"},
     *      description="Get all Branches",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Branch")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $branches = QueryBuilder::for(Branch::class)
            ->allowedFilters($this->branchRepository->getFieldsSearchable())
            ->allowedSorts($this->branchRepository->getFieldsSearchable())
            ->paginate();

        return $this->sendResponse(
            BranchResource::collection($branches),
            __('messages.retrieved', ['model' => __('models/branches.plural')])
        );
    }

    /**
     * @param CreateBranchAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/branches",
     *      summary="Store a newly created Branch in storage",
     *      tags={"Branch"},
     *      description="Store Branch",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Branch that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Branch")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Branch"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateBranchAPIRequest $request)
    {
        $input = $request->all();

        $branch = $this->branchRepository->create($input);

        return $this->sendResponse(
            new BranchResource($branch),
            __('messages.saved', ['model' => __('models/branches.singular')])
        );
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/branches/{id}",
     *      summary="Display the specified Branch",
     *      tags={"Branch"},
     *      description="Get Branch",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Branch",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Branch"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var Branch $branch */
        $branch = $this->branchRepository->find($id);

        if (empty($branch)) {
            return $this->sendError(
                __('messages.not_found', ['model' => __('models/branches.singular')])
            );
        }

        return $this->sendResponse(
            new BranchResource($branch),
            __('messages.retrieved', ['model' => __('models/branches.singular')])
        );
    }

    /**
     * @param int $id
     * @param UpdateBranchAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/branches/{id}",
     *      summary="Update the specified Branch in storage",
     *      tags={"Branch"},
     *      description="Update Branch",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Branch",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Branch that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Branch")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Branch"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateBranchAPIRequest $request)
    {
        $input = $request->all();

        /** @var Branch $branch */
        $branch = $this->branchRepository->find($id);

        if (empty($branch)) {
            return $this->sendError(
                __('messages.not_found', ['model' => __('models/branches.singular')])
            );
        }

        $branch = $this->branchRepository->update($input, $id);

        return $this->sendResponse(
            new BranchResource($branch),
            __('messages.updated', ['model' => __('models/branches.singular')])
        );
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/branches/{id}",
     *      summary="Remove the specified Branch from storage",
     *      tags={"Branch"},
     *      description="Delete Branch",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Branch",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var Branch $branch */
        $branch = $this->branchRepository->find($id);

        if (empty($branch)) {
            return $this->sendError(
                __('messages.not_found', ['model' => __('models/branches.singular')])
            );
        }

        $branch->delete();

        return $this->sendResponse(
            $id,
            __('messages.deleted', ['model' => __('models/branches.singular')])
        );
    }
}
