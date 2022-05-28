<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;

class OcbsController extends Controller
{
    public function update(Request $request)
    {
        $uuid = $request->uuid;

        $table = $request->table;

        if ($table == 'groups') {

            $update = Group::where('uuid', $uuid)->update($request->except(['table']));

        } else if ($table == 'users') {

            $update = 0;

        }
        

        if ($update) {
            return response(json_encode([
                'status' => 'ok',
                'message' => 'Updated'
            ]), 200);
        } else {
            return response(json_encode([
                'status' => 'error',
                'message' => 'Something went wrong'
            ]), 200);
        }
    }
}
