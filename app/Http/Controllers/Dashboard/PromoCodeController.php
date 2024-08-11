<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promos=PromoCode::where('expire_data','>',now())->paginate(10);
        return view('dashboard.promo.index',compact('promos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.promo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            $request->validate([
                'code'=>'required|string|min:3',
                'expire_date'=>'after:now',
                'discount'=>'required|integer',
            ]);

            PromoCode::create($request->all());
            return redirect()->route('dashboard.promo.index')->with('success','promo code created successfully');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.promo.index')
            ->with('fail', 'Not Created,Please Try Again');
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $promo=PromoCode::findOrFail($id);
            $promo->delete();
            return redirect()->back()->with('success','Deleted Successfully');
        }catch (\Exception $e) {
            return redirect()->route('dashboard.promo.index')
            ->with('fail', 'Not deleted ,Please Try Again');
            throw $e;
        }
    }
}
