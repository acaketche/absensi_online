<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function index(Request $request){
        $payments = Payment::where('id_student', $request->user()->id_student)
            ->with(['academicYear', 'student'])
            ->get();

        if ($payments->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No payments found',
                'data' => []
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Payments retrieved successfully',
            'data' => [
                'history-payments' => $payments,
            ]
        ]);
    }
}
