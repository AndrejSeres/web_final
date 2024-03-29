@extends('layouts.app')

@section('content')
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

    <div class="container">
        <div class="row justify-content-center">
            <div> <!-- Adjusted col-md-10 class -->
                <div class="card">
                    <div class="card-header">{{ __('home.dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('home.logged-in') }}

                        @auth
                            <div>
                                {{ __('home.current-user') }} {{ auth()->user()->name }}
                            </div>

                            <div id="table-container" class="mt-3"></div>
                            @if (auth()->user()->role === 'teacher')
                                <div id="table-container-all-students">
                                    <table id="students-table" class="table">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Generated Tasks</th>
                                            <th>Delivered Tasks</th>
                                            <th>Points</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            @else
                                <div>
                                    <button id="generate-tasks-button"
                                            class="btn btn-primary">{{ __('home.generate') }}</button>
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

        const generateTasksButton = document.getElementById('generate-tasks-button');
        const taskContainer = document.getElementById('task-container');

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
                        ${task.image ? `
                            <div class="card-img text-center">
                                <img src="${task.image}" alt="Task Image" style="max-width: 100%; height: auto;">
                            </div>
                        ` : ''}
                        <div class="form-group">
                            <label for="solution_${task.id}">{{ __('home.solution') }}</label>
                            <span id="math-field" class="math-fieldClass"></span>
                        </div>
                        <div class="form-group">
                            <label>{{ __('home.points') }}${task.points}</label>
                        </div>
						<div class="form-group">
                    <button id="submitBtn_${task.id}" class="btn btn-primary" onclick="submitSolution(${task.id})">Submit Solution</button>
                </div>
                    `;

                        // Append the card body to the task card
                        taskCard.appendChild(cardBody);

                        // Append the task card to the task container
                        taskContainer.appendChild(taskCard);
                    });

                    var mathFieldSpan = document.getElementById('math-field');
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

                    //OUTPUT Latex file is in the variable latexContent
                    console.log(mathFieldSpan.dataset.latexContent);

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


        function submitSolution(taskId) {
            const mathFieldSpan = document.getElementById(`math-field`);
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
                        data: { taskId, result, points, userSolution: latexContent },
                        success: function (response) {
                            // Update the UI or perform any necessary actions on success
                            console.log('User task updated successfully!');
                            Swal.fire({
                                icon: 'success',
                                title: '{{ __("home.success") }}',
                                text: '{{ __("home.success_msg") }}',
                            }).then(() => {
                                location.reload(); // Refresh the page
                            });
                        },
                        error: function (error) {
                            // Handle error scenario
                            console.error('Error updating user task:', error);
                            Swal.fire({
                                icon: 'error',
                                title: '{{ __("home.error") }}',
                                text: '{{ __("home.error_msg") }}',
                            }).then(() => {
                                location.reload(); // Refresh the page
                            });s

                        },
                    });
                })
                .catch(error => {
                    console.log(taskId + " " + latexSolution);
                    console.error('Error submitting solution:', error);
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("home.error") }}',
                        text: '{{ __("home.error_msg") }}',
                    }).then(() => {
                        location.reload(); // Refresh the page
                    });
                });
        }


    </script>

    <script>
        // JavaScript to show all students to the teacher

        // Function to display the students in the table
        function displayStudents(students) {
            // Get the table container element
            const tableContainer = document.getElementById('table-container');

            // Create the table element
            const table = document.createElement('table');
            table.classList.add('table');

            // Create the table body
            const tableBody = document.createElement('tbody');

            // Loop through the students and create table rows
            students.forEach(student => {
                // Create a table row
                const row = document.createElement('tr');

                // Create table cells for id, name, and email
                const idCell = document.createElement('td');
                idCell.textContent = student.id;

                const nameCell = document.createElement('td');
                nameCell.textContent = student.name;

                const emailCell = document.createElement('td');
                emailCell.textContent = student.email;

                // Append the cells to the row
                row.appendChild(idCell);
                row.appendChild(nameCell);
                row.appendChild(emailCell);

                // Append the row to the table body
                tableBody.appendChild(row);
            });

            // Append the table body to the table
            table.appendChild(tableBody);

            // Clear the table container
            tableContainer.innerHTML = '';

            // Append the table to the table container
            tableContainer.appendChild(table);
        }

        // Get the show table button and attach the click event listener
        const showStudentsButton = document.getElementById('show-students-button');
        showStudentsButton.addEventListener('click', () => {
            // Make an AJAX request to fetch the students
            fetch('/show-students')
                .then(response => response.json())
                .then(students => {
                    // Call the function to display the students in the table
                    displayStudents(students);
                })
                .catch(error => {
                    console.error('Error fetching students:', error);
                });
        });
    </script>

@endsection

