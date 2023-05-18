@extends('layouts.app')

@section('content')
    <div class="tasks-container">
        @foreach(auth()->user()->tasks as $task)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $task->name }}</h5>
                    <p class="card-text">{{ $task->description }}</p>
                    <p class="card-text">{{ $task->formula }}</p>
                    @if($task->image)
                        <div class="card-img text-center">
                            <img src="{{ $task->image }}" alt="Task Image" style="max-width: 100%; height: auto;">
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="solution_{{ $task->id }}">{{ __('home.solution') }}</label>
                        <input type="text" class="form-control" id="solution_{{ $task->id }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('home.points') }}{{ $task->points }}</label>
                    </div>
                    @if($task->state == 'delivered')
                        <div class="flag-icon flag-icon-success"></div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection
