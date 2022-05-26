<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Http\Requests\GroupRequest;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getActiveGroups()
    {
        $groups = Group::select('groups.id','groups.name','groups.uuid','groups.group_type','owner','contact','code','provinces.site as site','provinces.name as province_name','active_staff','installed_pc','address')
                    ->leftjoin('provinces','provinces.id','groups.province_id')
                    ->where('groups.is_active', 1)
                    ->get();

        return GroupResource::collection($groups);
    }

    public function getDeactivatedGroups()
    {
        $groups = Group::select('groups.id','groups.name','groups.uuid','groups.group_type','owner','contact','code','provinces.site as site','provinces.name as province_name','active_staff','installed_pc','address')
                    ->leftjoin('provinces','provinces.id','groups.province_id')
                    ->where('groups.is_active', 0)
                    ->get();

        return GroupResource::collection($groups);
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
    public function show(Group $group)
    {
        $group = Group::select('groups.id','groups.uuid','groups.name','groups.uuid','groups.group_type','owner','contact','code','provinces.site as site','provinces.name as province_name','active_staff','installed_pc','address','guarantor')
                    ->leftjoin('provinces','provinces.id','groups.province_id')
                    ->where('groups.id', $group->id)
                    ->first();
        return new GroupResource($group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GroupRequest $request, Group $group)
    {
        $group->update($request->validated());

        return new GroupResource($group);
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
