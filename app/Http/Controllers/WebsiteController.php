<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\User;
use App\Models\Website;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{

    public function assignPlan(Request $request){
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'expire_at'=>'nullable|date'
        ]);
        $user = User::find($request->user_id);
        $plan = Plan::find($request->plan_id);
        $user->plans()->attach($plan->id, [
            'start_date' => now(),
            'end_date' => $request->input('expire_at')?? now()->addDays($plan->default_days), // or based on plan duration
        ]);
        return response()->json(['message' => 'Plan assigned successfully']);
    }

    // public function checkDomain(Request $request)
    // {
    //     $request->validate([
    //         'domain' => 'required|string',
    //         'user_id' => 'required|exists:users,id',
    //     ]);
    //     $exists = Website::where('user_id', $request->user_id)
    //             ->where('domain', $request->domain)
    //             ->exists();
    //     return response()->json(['exists' => $exists]);
    // }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created website
     * Middlware already filter the plan permission
     * @version
     * @author Utkarsh Shukla <email>
     */
    public function store(Request $request)
    {
        $request->validate([
            'domain' => 'required|string|unique:websites,domain',
            'content' => 'nullable|array',
            'tracking_code' => 'nullable|string|max:255',
        ]);

        $user = $request->user();

        $website = new Website();
        $website->user_id = $user->id;
        $website->domain = $request->input('domain');
        $website->content = $request->input('content', []);
        $website->tracking_code = $request->input('tracking_code');
        $website->status = true;
        $website->save();

        return response()->json([
            'message' => 'Website created successfully',
            'website' => $website
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Website $website)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Website $website)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Website $website)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Website $website)
    {
        //
    }
}
