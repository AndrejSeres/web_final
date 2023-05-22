@php use App\Models\UserTask; @endphp
@extends('layouts.app')

@section('content')
    <div class="container mt-4">
    <h3>{{ $student->name }}</h3>
    <div class="tasks-container">
        <div class="accordion" id="accordionExample">
            @foreach($student->tasks as $task)
                @php
                    $userTask = UserTask::where('user_id', $student->id)
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
                            <span id="points-{{ $task->id }}">{{ __('home.points') }}{{$points}}</span>
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
                                <label>{{ __('home.solution') }}</label>
                                <p>$${{ $userTask->solution }}$$</p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('home.points') }}{{ $points }} </label>
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    // Function to update the points for a user task
    function updatePoints(userTaskId, taskId) {
    const pointsInput = document.getElementById(`points-input-${taskId}`);
    const points = parseInt(pointsInput.value);

    const url = `/update-points/${userTaskId}/${taskId}`;

    const headers = new Headers();
    headers.append("Content-Type", "application/json");
    headers.append("X-CSRF-TOKEN", window.csrfToken);

    const body = JSON.stringify({ points });

    const options = {
        method: "PUT",
        headers,
        body,
    };

    fetch(url, options)
        .then((response) => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error("Error updating points. Status: " + response.status);
            }
        })
        .then((data) => {
            console.log("Points updated successfully:", data.points);
            const pointsSpan = document.getElementById(`points-${taskId}`);
            pointsSpan.textContent = points;
        })
        .catch((error) => {
            console.error("Error updating points:", error);
        });
}

</script>

@endsection
