@extends('layouts.app')

@section('content')
    <header class="site-header">
        @include('layouts.nav')
    </header>
    <main>
        <section>
            <div class="container">
                <div class="row">
                    <div class="mt-md-5 mb-md-5">
                        <h3 class="mb-5 mt-5">{{ __('home.welcome') }}</h3>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer>
    </footer>

    <div class="container">
        <div class="row justify-content-center">
            <div> <!-- Adjusted col-md-10 class -->
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('home.logged-in') }}

                        @auth
                            <div>
                                Current User: {{ auth()->user()->name }}
                            </div>

                            @if (auth()->user()->role === 'teacher')
                                <div>
                                    Teacher options
                                </div>
                            @else
                                <div>
                                    <button id="generate-tasks-button" class="btn btn-primary">Generate Tasks</button>
                                </div>
                                <div id="task-container" class="mt-3"></div>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>


<script>
        // JavaScript code to handle the task generation and display

        // Get the generate tasks button
        const generateTasksButton = document.getElementById('generate-tasks-button');

        // Get the task container
        const taskContainer = document.getElementById('task-container');

        // Generate and display tasks when the button is clicked
        generateTasksButton.addEventListener('click', () => {
            // Make an AJAX request to fetch the tasks
            fetch('/generate-tasks')
                .then(response => response.json())
                .then(tasks => {
                    // Clear the task container
                    taskContainer.innerHTML = '';

                    // Loop through the tasks and create task cards
                    tasks.forEach(task => {
                        // Create the task card div
                        const taskCard = document.createElement('div');
                        taskCard.classList.add('card', 'mb-3');

                        // Create the card body
                        const cardBody = document.createElement('div');
                        cardBody.classList.add('card-body');

                        // Set the card content
                        cardBody.innerHTML = `
                        <h5 class="card-title">${task.name}</h5>
                        <p class="card-text">${task.description}</p>
                        <p class="card-text">${task.formula}</p>
                        <div class="card-img text-center">
                            <img src="${task.image}" alt="Task Image" style="max-width: 100%; height: auto;">
                        </div>
                        <div class="form-group">
                            <label for="solution_${task.id}">Solution</label>
                            <input type="text" class="form-control" id="solution_${task.id}">
                        </div>
                    `;



                        // Append the card body to the task card
                        taskCard.appendChild(cardBody);

                        // Append the task card to the task container
                        taskContainer.appendChild(taskCard);
                    });

                    // Render MathJax to display LaTeX formulas
                    MathJax.typeset()
                        .then(() => {
                            console.log('MathJax typesetting complete.');
                        })
                        .catch((err) => {
                            console.error('Error rendering MathJax:', err);
                        });
                })
                .catch(error => {
                    console.error('Error fetching tasks:', error);
                });
        });
    </script>

@endsection
