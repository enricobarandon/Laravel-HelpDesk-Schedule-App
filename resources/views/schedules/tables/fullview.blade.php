@php
    $groupCount = 1;
@endphp

<table class="table table-bordered display-center" id="table_id" cellspacing="0">
    <thead>
    </thead>
    <tbody>
        <tr>
            <td colspan="9">{{ strtoupper($scheduleInfo->name) }} ({{ date('l, M d Y', strtotime($scheduleInfo->date_time)) }})</td>
        </tr>
        <tr></tr>
        {!! $tbody !!}
    </tbody>
</table>