<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestModel;

class RequestController extends Controller
{
    public function index()
    {
        // $requests = RequestModel::select('operation','status','data')->get();
        $requests = RequestModel::select('requests.id','requests.operation','requests.status','requests.data','groups.name as group_name','accounts.username','requests.remarks')
                        ->leftjoin('groups','groups.uuid', 'requests.uuid')
                        ->leftjoin('accounts','accounts.uuid','requests.uuid')
                        ->orderBy('id','desc')
                        ->paginate(50);
        return view('requests.index', ['requests' => $requests]);
    }
}
