@section('page_heading','測試結果列表')

@extends('admin.layouts.dashboard')

@section('section')
    <table class="table table-bordered" id="suite">
        <thead>
        <tr>
            <th>suiteId</th>
            <th>created_at</th>
            <th>updated_at</th>
            <th>source</th>
            <th>id</th>
            <th>name</th>
            <th>status</th>
            <th>endTime</th>
            <th>startTime</th>
            <th>criticalTestsFail</th>
            <th>criticalTestsPass</th>
            <th>allTestsFail</th>
            <th>allTestsPass</th>
            <th>error</th>
            <th>Detail</th>
        </tr>
        </thead>
    </table>
@stop

@push('scripts')
<script>
    $(function() {
        $('#suite').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('datatables.suite') !!}',
            columns: [
                { data: 'suiteId', name: 'suiteId' },
                { data: 'created_at', name: 'created_at' },
                { data: 'updated_at', name: 'updated_at' },
                { data: 'source', name: 'source' },
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'status', name: 'status' },
                { data: 'endTime', name: 'endTime' },
                { data: 'startTime', name: 'startTime' },
                { data: 'criticalTestsFail', name: 'criticalTestsFail' },
                { data: 'criticalTestsPass', name: 'criticalTestsPass' },
                { data: 'allTestsFail', name: 'allTestsFail' },
                { data: 'allTestsPass', name: 'allTestsPass' },
                { data: 'error', name: 'error' },
                { data: 'detail', name: 'detail' }
            ]
        });
        $('#suite').DataTable().on('click', '.btn-detail[data-remote]', function (e) {
            alert("asdfa");
        });
    });
</script>
@endpush