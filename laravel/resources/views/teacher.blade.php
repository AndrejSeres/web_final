@extends('layouts.app')

@php
    $sets = \App\Models\Task::select('setId', 'date_from', 'date_to', 'open')->distinct('setId')->get();
    $setIds = \App\Models\Task::distinct('setId')->pluck('setId');
@endphp

@section('content')

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card" >
                    <div class="card-header">{{ __('teacher.tasks') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('tasks.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="setId">{{ __('teacher.set_id') }}</label>
                                <select name="setId" class="form-control" required>
                                    @foreach ($setIds as $setId)
                                        <option value="{{ $setId }}">{{ $setId }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="points">{{ __('teacher.points') }}</label>
                                <input type="number" name="points" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="date_from">{{ __('teacher.date_from') }}</label>
                                <input type="date" name="date_from" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="date_to">{{ __('teacher.date_to') }}</label>
                                <input type="date" name="date_to" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="open">{{ __('teacher.access') }}</label>
                                <select name="open" class="form-control" required>
                                    <option value="1">{{ __('teacher.open') }}</option>
                                    <option value="0">{{ __('teacher.closed') }}</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ __('teacher.update') }}</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 d-flex flex-column justify-content-between">
                    <div class="card-header">{{ __('teacher.upload_file') }}</div>
                    <div class="card-body">
                        <button type="button" class="btn btn-primary" onclick="callParsedData()">{{ __('teacher.call_parsed_data') }}</button>
                        <form method="POST" action="{{ route('upload.file') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="file">{{ __('teacher.upload_file') }}</label>
                                <input type="file" name="file" class="form-control-file" required>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('teacher.upload') }}</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        </div>
        <div class="row justify-content-center">

            <div class="col-md-7 mt-4">
                <div class="card">
                    <div class="card-header">{{ __('teacher.sets') }}</div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>{{ __('teacher.set_id') }}</th>
                                <th>{{ __('teacher.date_from') }}</th>
                                <th>{{ __('teacher.date_to') }}</th>
                                <th>{{ __('teacher.access') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($sets as $set)
                                <tr>
                                    <td>{{ $set->setId }}</td>
                                    @if ($set->date_from == null)
                                        <td>{{ __('teacher.not_set') }}</td>
                                    @else
                                        <td>{{ $set->date_from }}</td>
                                    @endif

                                    @if ($set->date_to == null)
                                        <td>{{ __('teacher.not_set') }}</td>
                                    @else
                                        <td>{{ $set->date_to }}</td>
                                    @endif

                                    @if ($set->open == 1)
                                        <td>{{ __('teacher.open') }}</td>
                                    @else
                                        <td>{{ __('teacher.closed') }}</td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>

    <script>
        function callParsedData() {
            fetch('/parsed-data')
                .then(response => {
                    // Handle the response from the server
                    if (response.ok) {
                        // Successful response
                        console.log('Parsed data called successfully');
                    } else {
                        // Error response
                        console.log('Error calling parsed data');
                    }
                })
                .catch(error => {
                    console.log('Error calling parsed data:', error);
                });
        }
    </script>
@endsection
