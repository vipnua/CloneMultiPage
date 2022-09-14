<?php

namespace App\Http\Controllers\Backend\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Feedback\FeedbackRequest;
use App\Model\Functions;
use App\Model\Feedback;
use App\Services\Feedback\FeedbackService;
class FeedbackController extends Controller
{
    private $feedbackService;
    public function __construct(FeedbackService $feedbackService)
    {
        $this->feedbackService = $feedbackService;
    }
    public function index(){
        $route_name = getRouteBaseName();
        $func = Functions::where('route', $route_name)->first();
        return view('backend.feedback.main', compact('func'));
    }
    public function show($id)
    {
        switch ($id) {
            case 'get-datatable':                          
                $this->authorize('viewAny', Feedback::class);        
                $feedback = Feedback::query()->latest();
                return datatables($feedback)->make(true);
                break;
            default:
                # code...
                break;
        }
    }
    public function update(FeedbackRequest  $request, $id)
    {
        dd('?');
        $this->authorize('update', Feedback::class);
        $data = $request->validated();
        $create = $this->feedbackService->updateFeedback($id, $data);
        if ($create) {
            return response()->json(
                [
                    'type' => 'success',
                    'title' => 'Success',
                    'content' => 'Edit successfully',
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
