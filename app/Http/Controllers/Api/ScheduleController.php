<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\ScheduleResource;
use App\Models\Schedule;
use App\Http\Requests\ScheduleRequest;
use App\Models\ActivityLog;

use Auth;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ScheduleResource::collection(Schedule::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ScheduleRequest $request)
    {
        $request->date_time = date('Y-m-d h:i:s', strtotime($request->date_time));
        $schedule = Schedule::create($request->validated());

        $user = Auth::user();

        // ActivityLog::create([
        //     'type' => 'store-schedule',
        //     'user_id' => Auth::id(),
        //     'assets' => json_encode([
        //         'action' => 'Created a schedule',
        //         'data' => json_encode($request->all())
        //     ])
        // ]);

        return new ScheduleResource($schedule);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        return new ScheduleResource($schedule);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ScheduleRequest $request, Schedule $schedule)
    {
        $schedule->update($request->validated());

        return new ScheduleResource($schedule);
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
}
