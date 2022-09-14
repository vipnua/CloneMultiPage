<?php

namespace App\Http\Controllers\Backend\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\DashboardRequest;
use App\Model\Functions;
use App\Model\PaymentMethod;
use App\Model\PaymentTransaction;
use App\Model\ReportDaily;
use App\Services\ReportPayment\ReportPaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportPaymentController extends Controller
{

    protected $reportPaymentService;

    public function __construct(ReportPaymentService $reportPaymentService)
    {
        $this->reportPaymentService = $reportPaymentService;
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


        $time = [Carbon::now()->startOfMonth(),Carbon::now()->endOfMonth()];
        $report = $this->reportPaymentService->getDataDashboard($time);

//        dd($report);
//        foreach ($report as $key => $value) {
//            echo $value->id . "-";
//            echo ($value->payment_transaction->sum('amount'));
//            echo ($value->payment_transaction->count());
//            echo "<br>";
//        }
//        die();
//        $paymentMethodReport = DB::table('payment_transactions')
//            ->whereMonth('payment_transactions.created_at',Carbon::now()->month)
//            ->join('payment_methods','payment_transactions.payment_method_id','=','payment_methods.id')
//            ->select(
//                'payment_method_id',
//                'payment_methods.name',
//                DB::raw('sum(`amount`) as total_amount'),
//                'payment_methods.status',
//                DB::raw('count(*) as total_record')
//            )
//            ->groupBy('payment_method_id')
//            ->get()->toArray();


//        $paymentMethodReport = DB::table('payment_methods')
//            ->leftJoin('payment_transactions', function($leftJoin)
//            {
//                $leftJoin->on('payment_transactions.payment_method_id', '=', 'payment_methods.id')->whereMonth('payment_transactions.created_at',Carbon::now()->month);
//
//            })
//            ->select(
//                'payment_methods.id',
//                'payment_methods.name',
//                DB::raw('sum(`amount`) as total_amount'),
//                'payment_methods.status',
//                DB::raw('count(payment_transactions.payment_method_id) as total_record')
//            )
//            ->groupBy('payment_methods.id')
//
//            ->get()->toArray();

return view('backend.dashboard.report_payment.main',compact('func','report') );

//        dd($paymentMethodReport);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getReportByDate(DashboardRequest $request)
    {
        try {
            $timeStart = $this->reportPaymentService->formatDate($request->timeStart);
            $timeEnd = $this->reportPaymentService->formatDate($request->timeEnd);

            $report = $this->reportPaymentService->getDataDashboard([$timeStart,$timeEnd]);
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
