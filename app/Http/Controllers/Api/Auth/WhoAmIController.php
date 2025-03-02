<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Auth\WhoAmIResource;
use Illuminate\Http\Request;

class WhoAmIController extends Controller
{
    /**
     * Get currently authenticated user
     */
    public function whoAmI(Request $request)
    {
        return response()->json(new WhoAmIResource($request->user()));
    }
}
