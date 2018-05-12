<?php

namespace App\Http\Controllers;

use App\Leave;
use App\User;
use Illuminate\Http\Request;

class LeavesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $request->user()->leaves()->orderBy('id', 'desc')->get();
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requester = $request->user(); 

        if ($count($requester->tasks()->get()) > 0) {
            return [
                'success' => false,
                'message' => 'You need to reassign tasks before create a leave request'
            ];
        }

        return Leave::create([
            'status' => 'requested',
            'type' => $request->get('type'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'substitute_id' => $request->get('substitute_id'),
            'user_id' => $request->user()->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function show(Leave $leave)
    {
        return $leave;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function edit(Leave $leave)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Leave $leave)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function destroy(Leave $leave)
    {
        //
    }
    
    public function approve(Request $request, Leave $leave)
    {
        $supervisor = $request->user();
        $subordinate = $leave->user()->first();

        if ($supervisor->id !== $subordinate->supervisor_id) {
            return false;
        }

        return [
            "success" => $leave->update([
                'status' => 'approved'
            ])
        ];
    }
    
    public function reject(Request $request, Leave $leave)
    {
        $supervisor = $request->user();
        $subordinate = $leave->user()->first();
    
        if ($supervisor->id !== $subordinate->supervisor_id) {
            return false;
        }
    
        return [
            "success" => $leave->update([
                'status' => 'rejected'
            ])
        ];
    }

    public function cancel(Request $request, Leave $leave)
    {
        $subordinate = $leave->user()->first();
        if ($subordinate->first()->id !== $request->user()->id) {
            return false;
        }

        return [
            "success" => $leave->update([
                'status' => 'cancelled'
            ])
        ];
    }
 
    public function leaveHistory(Request $request)
    {
        $subordinate = $request->user()->subordinates()->with('leaves.user')->get();
        $leaves = [];
        foreach ($subordinate as $user) {
            foreach ($user->leaves as $leave) {
                array_push($leaves, $leave);
            }
        }
        return $leaves;
    }

}
