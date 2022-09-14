<?php

namespace App\Http\Controllers\Backend\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Plan\PlanRequest;
use App\Model\Functions;
use App\Model\Plan;
use App\Services\Plan\PlanService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanManagerController extends Controller
{
    public $planService;

    public function __construct(PlanService $planService)
    {
        $this->planService = $planService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Plan::class);
        $route_name = getRouteBaseName();
        $func = Functions::where('route', $route_name)->first();
        return view('backend.plan.main', compact('func'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @param PlanRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(PlanRequest $request)
    {
//        $request = $request->validate();
        $this->authorize('create', Plan::class);
        $plan = $this->planService->addPlan($this->planService->formatRequest($request));
        if ($plan) {
            return response()->json(
                [
                    'type' => 'success',
                    'title' => 'Success',
                    'content' => 'Added successfully',
                ]
            );
        }
        return response()->json(
            [
                'type' => 'error',
                'title' => 'Error',
                'content' => 'Having trouble, try again later',
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        switch ($id) {
            case 'get-datatable':
                $this->authorize('viewAny', Plan::class);
                $plans = Plan::select('id', 'uuid', 'name', 'price', 'price_vn', 'interval', 'profile', 'profile_share', 'default', 'describe','status', 'interval_type')->latest();
                if ($request->name_search != null) {
                    $param = $request->name_search;
                    $plans = $plans->where(function ($query) use ($param) {
                        $query->where('name', 'like', '%' . $param . '%');
                    });
                }
                if ($request->default_search != null && $request->default_search != 'all') {
                    $plans = $plans->where('default', $request->default_search);
                }
                return datatables($plans)->make(true);
                break;
            default:
                # code...
                break;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update', Plan::class);
        $plan = $this->planService->findPlanById($id);
        if (!$plan) {
            return response()->json(
                [
                    'type' => 'error',
                    'title' => 'Error',
                    'content' => 'Resource does not exist or you don\'t have permission to access this resource.',
                ]
            );
        }
        $data = $this->planService->formatRequest($request, $plan->uuid);
        if ($this->planService->updatePlan($plan, $data)) {
            return response()->json(
                [
                    'type' => 'success',
                    'title' => 'Success',
                    'content' => [],
                ]
            );
        }
        return response()->json(
            [
                'type' => 'error',
                'title' => 'Error',
                'content' => 'Having trouble, try again later',
            ]
        );

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->authorize('delete', Plan::class);
        $plan = $this->planService->findPlanById($id);
        if (!$plan) {
            return response()->json(
                [
                    'type' => 'error',
                    'title' => 'Error',
                    'content' => 'Resource does not exist or you don\'t have permission to access this resource.',
                ]
            );
        }

        if ($this->planService->destroyPlan($plan)) {
            # code...
            return response()->json(
                [
                    'type' => 'success',
                    'title' => 'Success',
                    'content' => 'Delete plan successfully.',
                ]
            );
        };
        return response()->json(
            [
                'type' => 'error',
                'title' => 'Error',
                'content' => 'Having trouble, try again later',
            ]
        );
    }

    public function setDefault(PlanRequest $request)
    {
        $this->authorize('setDefault', Plan::class);
        $plan = $this->planService->findPlanById($request->id);

        if (!$plan) {
            return response()->json(
                [
                    'type' => 'error',
                    'title' => 'Error',
                    'content' => 'Resource does not exist or you don\'t have permission to access this resource.',
                ]
            );
        }

        DB::beginTransaction();
        try {
            $this->planService->updatePlan($this->planService->getPlanDefault(), ['default' => 0]);
            $this->planService->updatePlan($plan, ['default' => 1]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    'type' => 'error',
                    'title' => 'Error',
                    'content' => 'Having trouble, try again later',
                ]
            );
        }
        return response()->json(
            [
                'type' => 'success',
                'title' => 'Success',
                'content' => 'Delete plan successfully.',
            ]
        );
    }

    public function getAllPlan()
    {
        $this->authorize('viewAny', Plan::class);

        $plans = Plan::all();
        return response()->json(
            [
                'type' => 'success',
                'title' => 'Success',
                'content' => $plans,
            ]
        );
    }
}
