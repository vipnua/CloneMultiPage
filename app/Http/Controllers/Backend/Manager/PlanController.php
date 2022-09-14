<?php

namespace App\Http\Controllers\Backend\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\UserPlanRequest;
use App\Jobs\CreateChanrgeUpdateTransaction;
use App\Model\Charge;
use App\Model\PaymentMethod;
use App\Model\PaymentTransaction;
use App\Model\Plan;
use App\Services\PaymentTransaction\PaymentTransactionService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\Plan\ChargeService;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    private $chargeService;
    private $paymentTransactionService;

    public function __construct(ChargeService $chargeService, PaymentTransactionService $paymentTransactionService)
    {
        $this->chargeService = $chargeService;
        $this->paymentTransactionService = $paymentTransactionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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


    public function store(UserPlanRequest $request)
    {
        $request = $request->validated();

        $user = User::find($request['user']);
        if (!$user) {
            return response()->json(
                [
                    'type' => 'error',
                    'title' => 'error',
                    'content' => "User not found.",
                ]
            );
        }

        $plan = Plan::find($request['plan']);
        if (!$plan) {
            return response()->json(
                [
                    'type' => 'error',
                    'title' => 'error',
                    'content' => "Plan not found.",
                ]
            );
        }
        if ($request['currency'] == 'VND') {
            $price = ((float)$plan->price_vn);
        } else {
            $price = ((float)$plan->price);
        }
        if ($price != 0) {
            $paymentDividedInterval = (float)$request['amount'] / $price;

            if ($paymentDividedInterval && $paymentDividedInterval > 20) {
                return response()->json(
                    [
                        'type' => 'error',
                        'title' => 'error',
                        'content' => "Can't extend too much.",
                    ]
                );
            }
        }

        $paymentMethod = PaymentMethod::find($request['payment_method']);
        if (!$paymentMethod) {
            return response()->json(
                [
                    'type' => 'error',
                    'title' => 'error',
                    'content' => "Payment method not found.",
                ]
            );
        }
        //add payment transaction
        $data = $this->paymentTransactionService->formatDataCreateFromManager($request);

        if (!$plan->interval) {
            return response()->json(
                [
                    'type' => 'error',
                    'title' => 'error',
                    'content' => "Can't add plan to this user",
                ]
            );
        }
        $paymentTransaction = $this->paymentTransactionService->createNewPaymentTransaction($data);

        CreateChanrgeUpdateTransaction::dispatch($paymentTransaction);

        if ($paymentTransaction) {
            return response()->json(
                [
                    'type' => 'success',
                    'title' => 'Success',
                    'content' => 'Add plan successfully',
                ]
            );
        }
        return response()->json(
            [
                'type' => 'error',
                'title' => 'error',
                'content' => "Can't add plan to this user",
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
//        $listChrage = $this->chargeService->getListCharge($id);
//        $listChrage = PaymentTransaction::where('user_id', $id)->with('plan','charge')->latest('id');
        $listChrage = Charge::where('user_id', $id)->with('paymentTransaction')->latest('id');
        return datatables($listChrage)->make(true);
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
