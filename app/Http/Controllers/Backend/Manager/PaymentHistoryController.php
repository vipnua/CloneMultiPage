<?php

namespace App\Http\Controllers\Backend\Manager;

use App\Http\Controllers\Controller;
use App\Model\Functions;
use App\Model\PaymentMethod;
use App\Model\PaymentTransaction;
use App\Model\ReportDaily;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $route_name = getRouteBaseName();
        $func = Functions::where('route', $route_name)->first();


        return view('backend.payment_history.main', compact('func'));
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
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        switch ($id) {
            case 'get-datatable':
                $payment_transaction = PaymentTransaction::leftJoin('users', 'payment_transactions.user_id', '=', 'users.id')
                    ->leftJoin('plans', 'payment_transactions.plan_id', '=', 'plans.id')
                    ->leftJoin('admins', 'payment_transactions.employee_id', '=', 'admins.id')
                    ->select('payment_transactions.id', 'payment_transactions.transaction_id', 'users.name as user_name', 'plans.name as plan_name', 'amount', 'currency', 'payment_date', 'employee_id', 'admins.name as employee_name', 'note', 'system_note', 'charge_id')
                    ->latest('payment_transactions.created_at');
//                    ->get();

                if ($request->name_search != null) {
                    $param = $request->name_search;
                    $payment_transaction = $payment_transaction->where(function ($query) use ($param) {
                        $query
                            ->where('payment_transactions.transaction_id', 'like', '%' . $param . '%')
                            ->orWhere('payment_transactions.id', 'like', '%' . $param . '%')
                            ->orWhere('users.name', 'like', '%' . $param . '%');
                    });
                }
                if ($request->status_search != null && $request->status_search != 'all') {
                    switch ($request->status_search) {
                        case 0:
                            $payment_transaction = $payment_transaction
                                ->where( 'payment_transactions.payment_date','<>', null )
                                ->where( 'charge_id','=', null );
                            break;
                        case 1:
                            $payment_transaction = $payment_transaction
                                ->where('payment_date','<>', null)
                            ->where( 'charge_id','<>', null );
                            break;
                        case 2:
                            $payment_transaction = $payment_transaction->where('payment_date', null);
                            break;


                        default:
                            # code...
                            break;
                    }

                }
                return datatables($payment_transaction)->make(true);
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
}
