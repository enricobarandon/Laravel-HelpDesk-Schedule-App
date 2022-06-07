<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use Excel;
use App\Exports\ScheduledGroupExport;
use App\Models\Province;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $groups = Group::select('groups.name','is_active','group_type','code','owner','contact','provinces.name as province','provinces.site as site','active_staff','installed_pc')
                        ->join('provinces','provinces.id','groups.province_id');

        if ($request->has('download-active') || $request->has('download-deactivated')) {

            if($request->has('download-active')){
                $groupsStatus = 'active';
                $groups = $groups->where('is_active', 1)->get();
            }else{
                $groupsStatus = 'deactivated';
                $groups = $groups->where('is_active', 0)->get();
            }
            return Excel::download(
                new ScheduledGroupExport('groups.tables.groupsTable', [
                    'groups' => $groups
                ]),
                $groupsStatus.'-groups.xlsx'
            );
        }

        $groups = $groups->get();

        return view('groups.index', ['groups' => $groups]);
        // return view('groups.index');
    }

    public function create()
    {
        $provinces = Province::all();
        return view('groups.create', compact('provinces'));
    }
}
