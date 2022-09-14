<?php

namespace App\Http\Controllers\Backend\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\PaymentMethodRequest;
use App\Model\Functions;
use App\Model\PaymentMethod;
use App\Services\PaymentMethod\PaymentMethodService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentMethodController extends Controller
{
    public $paymentMethodService;

    public function __construct(PaymentMethodService $paymentMethodService)
    {
        $this->paymentMethodService = $paymentMethodService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', PaymentMethod::class);
        $route_name = getRouteBaseName();
        $func = Functions::where('route', $route_name)->first();
        return view('backend.payment_method.main', compact('func'));
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
     * @param PaymentMethodRequest $request
     * @return \Illuminate\Http\JsonResponse|void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(PaymentMethodRequest $request)
    {
        $this->authorize('create', PaymentMethod::class);
        $request = $request->validated();
        switch ($request['method']) {
            case 'bank':
                $file_icon = $request['icon'];
                $ext_directory = PaymentMethod::PUB_ICON_DIRECTORY;
                $icon_file_name = md5(Str::uuid()) . "." . $file_icon->extension();
                $file_icon->move(public_path($ext_directory), $icon_file_name);

                $data = [
                    'info' => [
                        'branch_name' => $request['branch_name'],
                        'account_number' => $request['account_number'],
                    ],
                    'icon' => $icon_file_name,
                ];
                break;
            case 'paypal':
                $data = [
                    'info' => [
                        'client_id' => $request['client_id'],
                        'secret_key' => $request['secret_key'],
                        'type' => $request['paypal_sandbox'] === "disable" ? "live" : "sandbox",
                    ],
                    'icon' => 'paypal.jpg',
                ];
                break;

            default:
                return response()->json(
                    [
                        'type' => 'error',
                        'title' => 'Error',
                        'content' => 'Having trouble, try again later',
                    ]
                );
        }

        $data['method'] = PaymentMethod::METHOD[$request['method']];
        $data['country'] = $request['country'];
        $data['name'] = $request['name'];
        $data['status'] = PaymentMethod::STATUS[$request['status']];

        if ($this->paymentMethodService->createNewPaymentMethod($data)) {
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
                $this->authorize('viewAny', PaymentMethod::class);
                $payment_method = PaymentMethod::select('id', 'method', 'country', 'info', 'status', 'name', 'icon')->latest();
                if ($request->name_search != null) {
                    $param = $request->name_search;
                    $payment_method = $payment_method->where(function ($query) use ($param) {
                        $query->where('name', 'like', '%' . $param . '%');
                    });
                }
                if ($request->status_search != null && $request->status_search != 'all') {
                    $payment_method = $payment_method->where('status', $request->status_search);
                }
//                dd($payment_method);
                return datatables($payment_method)->make(true);
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
    public function update(PaymentMethodRequest $request, $id)
    {
        $this->authorize('update', PaymentMethod::class);
        $data = $request->validated();

        $paymentMethod = $this->paymentMethodService->getPaymentMethodByID($id);

        if (!$paymentMethod) {
            return response()->json(
                [
                    'type' => 'error',
                    'title' => 'Error',
                    'content' => 'Payment method does not exist.',
                ]
            );
        }

        switch ($request['method']) {
            case 'bank':
                if (isset($request['icon'])) {
                    $file_icon = $request['icon'];
                    $ext_directory = PaymentMethod::PUB_ICON_DIRECTORY;
                    $icon_file_name = md5(Str::uuid()) . "." . $file_icon->extension();
                    $file_icon->move(public_path($ext_directory), $icon_file_name);
                    if ($paymentMethod->icon != 'paypal.jpg' && file_exists(public_path() . '/payment_method/' . $paymentMethod->icon)) {
                        unlink(public_path() . '/payment_method/' . $paymentMethod->icon);
                    }
                    $data['icon'] = $icon_file_name;
                }

                $data['info'] = [
                    'branch_name' => $request['branch_name'],
                    'account_number' => $request['account_number'],
                ];
                break;
            case 'paypal':
                $data = [
                    'info' => [
                        'client_id' => $request['client_id'],
                        'secret_key' => $request['secret_key'],
                        'type' => $request['paypal_sandbox'] === "disable" ? "live" : "sandbox",
                        'token' => ""
                    ],
                    'icon' => 'paypal.jpg',
                ];
                break;

            default:
                return response()->json(
                    [
                        'type' => 'error',
                        'title' => 'Error',
                        'content' => 'Having trouble, try again later',
                    ]
                );
        }

        $data['method'] = PaymentMethod::METHOD[$request['method']];
        $data['country'] = $request['country'];
        $data['name'] = $request['name'];
        $data['status'] = PaymentMethod::STATUS[$request['status']];
//        dd(data);

        if ($this->paymentMethodService->updatePaymentMethod($paymentMethod, $data)) {
            return response()->json(
                [
                    'type' => 'success',
                    'title' => 'Success',
                    'content' => 'Update successfully',
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
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', PaymentMethod::class);
        if (!$this->paymentMethodService->getPaymentMethodByID($id)) {
            return response()->json(
                [
                    'type' => 'error',
                    'title' => 'Error',
                    'content' => 'Payment method does not exist.',
                ]
            );
        }
        if ($this->paymentMethodService->destroyPaymentMethodByID($id)) {
            return response()->json(
                [
                    'type' => 'success',
                    'title' => 'Success',
                    'content' => 'Delete successfully',
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
}
