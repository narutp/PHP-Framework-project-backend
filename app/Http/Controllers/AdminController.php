<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use App\Task;
use App\Leave;
use Ellumilel\ExcelWriter;
use Storage;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function generateSheet(Request $request)
    {
      $requester = $request->user();
      if (!$requester->is_admin) { return response(["message" => "unauthorized"], 401); }
      $supervisors = User::where('type', 'supervisor')->get();
      $subordinates = User::with('supervisor')->where('type', 'subordinate')->get();
      $admins = User::where('is_admin', true)->get();

      $tasks = Task::with('user', 'assignor', 'reassignee')->get();
      $leaves = Leave::get();

      $wExcel = new ExcelWriter();
      $wExcel->writeSheetHeader('Supervisors', [
        'id' => 'integer',
        'name' => 'string',
        'department' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'lineid' => 'string',
        'facebook' => 'string',
        'profile_picture' => 'string',
        'address' => 'string'
      ]);
      foreach ($supervisors as $supervisor) {
        $wExcel->writeSheetRow('Supervisors', [
          $supervisor->id,
          $supervisor->name,
          $supervisor->department,
          $supervisor->email,
          $supervisor->phone,
          $supervisor->lineid,
          $supervisor->facebook,
          $supervisor->profile_picture,
          $supervisor->address
        ]);
      }
          
      $wExcel->writeSheetHeader('Subordinates', [
        'id' => 'integer',
        'name' => 'string',
        'department' => 'string',
        'supervisor' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'lineid' => 'string',
        'facebook' => 'string',
        'profile_picture' => 'string',
        'address' => 'string'
      ]);
      
      foreach ($subordinates as $subordinate) {
        $wExcel->writeSheetRow('Subordinates', [
          $subordinate->id,
          $subordinate->name,
          $subordinate->department,
          $subordinate->supervisor === null ? "" : $subordinate->supervisor->name,
          $subordinate->email,
          $subordinate->phone,
          $subordinate->lineid,
          $subordinate->facebook,
          $subordinate->profile_picture,
          $subordinate->address
        ]);
      }
          
      $wExcel->writeSheetHeader('Admins', [
        'id' => 'integer',
        'name' => 'string',
        'department' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'lineid' => 'string',
        'facebook' => 'string',
        'profile_picture' => 'string',
        'address' => 'string'
      ]);
      foreach ($admins as $admin) {
        $wExcel->writeSheetRow('Admins', [
          $admin->id,
          $admin->name,
          $admin->department,
          $admin->email,
          $admin->phone,
          $admin->lineid,
          $admin->facebook,
          $admin->profile_picture,
          $admin->address
        ]);
      }

      $wExcel->writeSheetHeader('Tasks', [
        'id' => 'integer',
        'name' => 'string', 
        'description' => 'string',
        'start date' => 'YYYY-MM-DD',
        'end date' => 'YYYY-MM-DD',
        'assignee' => 'string',
        'assignor' => 'string',
      ]);
      foreach ($tasks as $task) {
        $wExcel->writeSheetRow('Tasks', [
          $task->id,
          $task->task_name,
          $task->description,
          $task->start_date,
          $task->end_date,
          $task->user->name,
          $task->assignor->name,
        ]);
      }

      $wExcel->writeSheetHeader('Leaves', [
        'id' => 'integer',
        'requester' => 'string',
        'type' => 'string',
        'status' => 'string',
        'start date' => 'YYYY-MM-DD',
        'end date' => 'YYYY-MM-DD'
      ]);
      foreach ($leaves as $leave) {
        $wExcel->writeSheetRow('Leaves', [
          $leave->id,
          $leave->user->name,
          $leave->type,
          $leave->status,
          $leave->start_date,
          $leave->end_date
        ]);
      }


      $wExcel->writeTofile('exported.xlsx');
      return response()->download('exported.xlsx', 'exported.xlsx');
    }
}