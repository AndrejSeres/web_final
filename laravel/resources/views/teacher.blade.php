@extends('layouts.app')

@php
    $setIds = \App\Models\Task::pluck('setId')->unique();
@endphp

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
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
                                <label for="date_from">{{ __('teacher.date_from') }}</label>
                                <input type="date" name="date_from" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="date_to">{{ __('teacher.date_to') }}</label>
                                <input type="date" name="date_to" class="form-control" required>
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
        </div>
    </div>
@endsection
