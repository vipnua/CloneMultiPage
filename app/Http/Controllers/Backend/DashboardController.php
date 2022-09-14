<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Model\Functions;
use Auth;
use App\Model\ReportDaily;
use App\Services\ReportDaily\ReportDailyService;
use App\Http\Requests\Dashboard\DashboardRequest;

class DashboardController extends Controller
{
    protected $reportDailyService;

    public function __construct(ReportDailyService $reportDailyService)
    {
        $this->reportDailyService = $reportDailyService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $url = getUrl();
        $func = Functions::where('route', $url)->first();

        $result = ReportDaily::where('date', Carbon::now()->toDateString());

        $report = $this->reportDailyService->getDataDashboard($result);

        return view('backend.dashboard.main', compact('func', 'report'));
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
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {

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
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReportByDate(DashboardRequest $request)
    {
        try {
            $timeStart = $this->reportDailyService->formatDate($request->timeStart);
            $timeEnd = $this->reportDailyService->formatDate($request->timeEnd);

            $result = ReportDaily::whereBetween('date', [$timeStart,$timeEnd]);
            $report = $this->reportDailyService->getDataDashboard($result);
            if ($report) {
                return response()->json(
                    [
                        'type' => res_type('success'),
                        'title' => 'Fillter success',
                        'content' => $report,
                    ],
                    res_code('200')
                );
            }
            return response()->json(
                [
                    'type' => res_type('error'),
                    'title' => 'Fillter error',
                    'content' => res_content('empty'),
                ],
                res_code('200')
            );

        } catch (\Exception $exception) {
            return response()->json(
                [
                    'type' => res_type('error'),
                    'title' => 'There was an error while executing, please try again.',
                    'content' => res_content('empty'),
                ],
                res_code('200')
            );
        }

    }
}
