@php use App\Models\UserTask; @endphp
@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="tasks-container">
            <div class="accordion" id="accordionExample">
                @foreach(auth()->user()->tasks as $task)
                    @php
                        $userTask = UserTask::where('user_id', auth()->user()->id)
                            ->where('task_id', $task->id)
                            ->first();

                        $state = $userTask ? $userTask->state : null;
                        $points = $userTask ? $userTask->points : null;
                    @endphp

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $task->id }}">
                            <button class="accordion-button collapsed
                                @if($state == 'delivered' && $points == 0)
                                    bg-danger text-white
                                @elseif($state == 'delivered' && $points > 0)
                                    bg-success text-white
                                @endif"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $task->id }}"
                                    aria-expanded="false"
                                    aria-controls="collapse{{ $task->id }}"
                            >
                                {{ __('home.points') }}{{$points}}
                            </button>
                        </h2>
                        <div id="collapse{{ $task->id }}" class="accordion-collapse collapse"
                             aria-labelledby="heading{{ $task->id }}" data-bs-parent="#accordionExample">
                            <div class="accordion-body bg-white rounded shadow-sm">
                                <h5 class="card-title">{{$task->name}}</h5>
                                <p>{{ $task->description }}</p>
                                <p>{{ $task->formula }}</p>
                                @if($task->image)
                                    <div class="card-img text-center">
                                        <img src="{{ $task->image }}" alt="Task Image"
                                             style="max-width: 100%; height: auto;">
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label for="solution_{{ $task->id }}">{{ __('home.solution') }}</label>
                                    <input type="text" class="form-control" id="solution_{{ $task->id }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('home.points') }}{{ $task->points }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
