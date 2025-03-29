<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pocket;
use Illuminate\Http\Request;

class PocketController extends Controller
{
    public function pocket(Request $request)
    {
        $query = Pocket::query();

        if( $request->user()->role == User::ROLE_USER )
        {
            $query->where('user_id', $request->user()->id);
        }

        return response()->json([
            'success' => true,
            'amount' => $query->sum('amount')
        ]);
    }
}
