// Function to redirect to the student detail page
function redirectToStudentDetail(studentId) {
    window.location.href = `/student-detail/${studentId}`;
}

// Function to display the students in the table
function displayStudents(students) {
    // Get the table container element for all students
    const tableContainer = document.getElementById("students-table");

    // Clear the table body
    tableContainer.getElementsByTagName("tbody")[0].innerHTML = "";

    // Loop through the students and create table rows for all students
    students.forEach((student) => {
        // Create a table row for all students
        const row = document.createElement("tr");

        // Create table cells for id, name, email, generated tasks, delivered tasks, and points for all students
        const idCell = document.createElement("td");
        idCell.textContent = student.id;

        const nameCell = document.createElement("td");
        nameCell.textContent = student.name;

        const emailCell = document.createElement("td");
        emailCell.textContent = student.email;

        const generatedTasksCell = document.createElement("td");
        generatedTasksCell.textContent = student.generatedTasks;

        const deliveredTasksCell = document.createElement("td");
        deliveredTasksCell.textContent = student.deliveredTasks;

        const pointsCell = document.createElement("td");
        pointsCell.textContent = student.points;

        // Add a click event listener to the row for all students
        row.addEventListener("click", () => {
            // Redirect to the student detail page
            redirectToStudentDetail(student.id);
        });

        // Append the cells to the row for all students
        row.appendChild(idCell);
        row.appendChild(nameCell);
        row.appendChild(emailCell);
        row.appendChild(generatedTasksCell);
        row.appendChild(deliveredTasksCell);
        row.appendChild(pointsCell);

        // Append the row to the table body for all students
        tableContainer.getElementsByTagName("tbody")[0].appendChild(row);
    });
}

// Wait for the DOM content to load
document.addEventListener("DOMContentLoaded", () => {
    // Make an AJAX request to fetch the students
    fetch("/show-students")
        .then((response) => response.json())
        .then((students) => {
            // Call the function to display the students in the table for all students
            displayStudents(students);
        })
        .catch((error) => {
            console.error("Error fetching students:", error);
        });
});
