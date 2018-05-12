<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Task::all();
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
    public function store(Request $request) {
        $task = Task::create([
            'task_name' => $request->get('task_name'),
            'description' => $request->get('description'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'assignee_id' => $request->get('assignee_id'),
            'assignor_id' => $request->user()->id
        ]);
        return $task;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return $task;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        //
    }

    public function delete(Task $task)
    {
        $task->delete();

        return response()->json(null, 204);
    }

    public function indexIncomplete(Request $request)
    {
        return $request->user()->tasks()->where('end_date', '>=', 'now()')->get();
    }

    public function reassign(Request $request, Task $task, User $user)
    {
        $requester = $request->user();

        $requester_supervisor = $requester->supervisor()->first();
        $reassignee_supervisor = $user->supervisor()->first();
        
        if ($requester_supervisor === null && $reassignee_supervisor === null) {
            return ['success' => false];
        }

        if ($requester_supervisor->id !== $reassignee_supervisor->id) {
            return ['success' => false];
        }

        return [
            'success' => $task->update([
                'reassignee_id' => $requester->id
            ])
        ];
    }

    public function indexReassigned(Request $request)
    {
        $requester = $request->user();
        
        return $requester->reassignTask()->get();
    }

    public function approveReassign(Request $request, Task $task)
    {
        $requester = $request->user();

        if ($requester->id === $task->reassignee_id or $task->reassignee_id === null) {
            return ['success' => false];
        }

        return [
            'success' => $task->update([
                'reassignee_id' => null,
                'assignee_id' => $requester->id
            ])
        ];
    }
    
    public function rejectReassign(Request $request, Task $task)
    {
        $request = $request->user();

        if ($requester->id === $task->reassignee_id or $task->reassignee_id === null) {
            return ['success' => false];
        }

        return [
            'success' => $task->update([
                'reassignee_id' => null
            ])
        ];
    }
}
