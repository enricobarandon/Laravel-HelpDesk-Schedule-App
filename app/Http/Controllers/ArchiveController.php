<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Archive;
use App\Models\ActivityLog;
use App\Http\Requests\ArchiveRequest;

use Auth;
use Excel;
use App\Exports\ScheduledGroupExport;

class ArchiveController extends Controller
{
    public function index(Request $request){

        $rows = Archive::orderBy('created_at','desc')->paginate(10);

        if ($request->has('download')) {
            return Excel::download(
                new ScheduledGroupExport('archive.tables.archive-table', [
                    'rows' => $rows
                ]),
                'archive.xlsx'
            );
        }

        return view('archive.index', compact('rows'));
    }

    public function add(){

        return view('archive.create');
    }

    public function create(){

    }

    public function edit(Request $request){

        $archiveId = $request->segment(3);
// dd($archiveId);
        $archiveInfo = Archive::where('id', $archiveId)->first();

        return view('archive.update', compact('archiveInfo'));
    }

    public function submitArchive(ArchiveRequest $request){

        $archiveId = $request->id;

        $user = Auth::user();

        $archive = [
            'bet_count' => $request->bet_count,
            'transaction_count' => $request->transaction_count,
            'date_covered' => $request->date_covered,
            'fg_link' => $request->fg_link,
            'schedule_link' => $request->schedule_link,
            'start' => $request->start,
            'end' => $request->end,
            'duration' => $request->duration,
            'requested_by' => $request->requested_by,
            'processed_by' => $request->processed_by,

        ];

        $op = $request->operation;

        if($op == 'create'){
            $createArchive = Archive::create($archive);

            if($createArchive){

                ActivityLog::create([
                    'type' => 'create-archive',
                    'user_id' => $user->id,
                    'assets' => json_encode($archive)
                ]);
                return redirect()->route('archive.index')->with('success','Archive successfully created.');

            }else{
                return back()->with('error', 'Something went wrong!');
            }
        }else if($op == 'update'){
            $updateArchive = Archive::where('id', $archiveId)->update($archive);

            if($updateArchive){

                ActivityLog::create([
                    'type' => 'update-archive',
                    'user_id' => $user->id,
                    'assets' => json_encode($archive)
                ]);
                return redirect()->route('archive.index')->with('success','Archive successfully updated.');

            }else{
                return back()->with('error', 'Something went wrong!');
            }
        }

    }
}
