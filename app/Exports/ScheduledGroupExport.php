<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ScheduledGroupExport implements FromView,ShouldAutoSize
{
    var $template;
    var $data;

    public function __construct($template, $data)
    {
        $this->template = $template;

        $data['downloading'] = true;
        
        $this->data = $data;
    }

    public function view(): View
    {
        return view($this->template, $this->data);
    }
}
