<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use Excel;
use App\Exports\ScheduledGroupExport;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $schedules = Schedule::select('id','name','date_time','created_at')->where('status','active')->orderBy('date_time','desc')->get();

        
        if ($request->has('download')) {
            return Excel::download(
                new ScheduledGroupExport('schedules.tables.scheduleTable', [
                    'schedules' => $schedules
                ]),
                'schedules.xlsx'
            );
        }
        return view('schedules.index', ['schedules' => $schedules]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('schedules.create', []);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('schedules.edit', []);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function pastSchedules(Request $request){
        $pastSchedules = Schedule::select('id','name','status','date_time','created_at')
                                ->where('status', 'finished')
                                ->orderBy('date_time','desc')
                                ->get();
                                
        if($request->has('download-finish')) {
            return Excel::download(
                new ScheduledGroupExport('schedules.tables.finish-schedule-table', [
                    'pastSchedules' => $pastSchedules
                ]),
                'past-schedules.xlsx'
            ); 
        }

        return view('schedules.past-schedules', ['pastSchedules' => $pastSchedules]);
    }
}
