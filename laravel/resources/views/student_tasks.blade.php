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
                        $solution = $userTask ? $userTask->solution : null;
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
                                    <label for="solution_{{ $task->id }}">{{ __('home.your_solution') }}</label>
                                    @if ($solution)
                                        <div id="math-field_{{ $task->id }}" class="math-fieldClass">
                                            $${{ $solution }}$$
                                        </div>
                                    @else
                                        <span id="math-field-{{ $task->id }}" class="math-fieldClass" style="width: 50px" onload="loadLatex({{$task->id }})"></span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ __('home.points') }}{{ $task->points }}</label>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary" onclick="submitSolution({{ $task->id }})">
                                        Submit Solution
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <script>
        window.onload = function() {

            @foreach(auth()->user()->tasks as $task)
            loadLatex({{ $task->id }});
            @endforeach
        };
    function loadLatex(id){
        var mathFieldSpan = document.getElementById('math-field-'+id);
        var latexContent;

        var MQ = MathQuill.getInterface(2); // for backcompat
        var mathField = MQ.MathField(mathFieldSpan, {
            spaceBehavesLikeTab: true, // configurable
            handlers: {
                edit: function() { // useful event handlers
                    latexContent = mathField.latex(); // simple API
                    console.log(latexContent);
                    mathFieldSpan.dataset.latexContent = latexContent;
                }
            }
        });
        MathJax.typeset();
    }


        function submitSolution(taskId) {
            const mathFieldSpan = document.getElementById(`math-field-`+taskId);
            const latexContent = mathFieldSpan.dataset.latexContent; // Retrieve the stored LaTeX content

            const csrfToken = window.csrfToken;
            console.log(latexContent);
            fetch('/compare-solution', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ taskId, latexSolution: latexContent }),
            })
                .then(response => response.json())
                .then(data => {
                    // Assuming the server responds with the result and points
                    const { result, points } = data;
                    console.log(result);
                    $.ajax('/update-user-task', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        data: { taskId, result, points, userSolution: latexContent},
                        success: function(response) {
                            // Update the UI or perform any necessary actions on success
                            console.log('User task updated successfully!');
                        },
                        error: function(error) {
                            // Handle error scenario
                            console.error('Error updating user task:', error);
                        },
                    });
                })
                .catch(error => {
                    console.log(taskId + " " + latexSolution);
                    console.error('Error submitting solution:', error);
                });
        }
    </script>


@endsection
