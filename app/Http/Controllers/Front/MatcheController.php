<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\MatcheResource;
use App\Models\Matche;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class MatcheController extends Controller
{
    use ApiTrait;
    public function index(Request $request)
    {
        $matches=Matche::where('quantity','>','0')->paginate(10);
        if (!$matches) {
            return $this->errorResponse(
                null,
                null,
                $request->getLocale()
            );
        }
        return $this->successResponse(
            MatcheResource::collection($matches)->response()->getData(),
            null,
            $request->getLocale()
        );
    }

    public function show(Request $request , $id)
    {
        $matche=Matche::findOrFail($id);
        if (!$matche) {
            return $this->errorResponse(
                null,
                null,
                $request->getLocale()
            );
        }
        return $this->successResponse(
            new MatcheResource($matche),
            null,
            $request->getLocale()
        );
    }
}
