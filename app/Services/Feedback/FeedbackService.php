<?php

namespace App\Services\Feedback;

use App\Model\Feedback;

class FeedbackService{

      public function updateFeedback($id, $data) {
          $feedback = Feedback::find($id)->update([
              'status' => $data['status'],
          ]);
          return $feedback;
      }
  
}