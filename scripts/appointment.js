// // Toggle the visibility of the appointment form
// function toggleForm() {
//     const form = document.getElementById("appointmentForm");
//     if (form.style.display === "none" || form.style.display === "") {
//         form.style.display = "block"; // Show the form
//     } else {
//         form.style.display = "none"; // Hide the form
//     }
// }

// // Handle Edit functionality
// function editAppointment(button) {
//     const row = button.closest("tr");
//     const clientName = row.cells[0].innerText;
//     const petName = row.cells[1].innerText;
//     const appointmentDate = row.cells[2].innerText;
//     const appointmentTime = row.cells[3].innerText;

//     // Populate the form with the selected appointment data
//     document.getElementById("clientName").value = clientName;
//     document.getElementById("petName").value = petName;
//     document.getElementById("appointmentDate").value = appointmentDate;
//     document.getElementById("appointmentTime").value = appointmentTime;

//     // Optionally change the form's action to update the appointment (if needed)
//     // You can add a hidden input field in the form to identify the appointment to update
//     const form = document.querySelector("form");
//     form.action = "update_appointment.php"; // Example: change the action URL for updating
// }

// // Handle Delete functionality
// function deleteAppointment(button) {
//     const row = button.closest("tr");
//     const clientName = row.cells[0].innerText;
//     const petName = row.cells[1].innerText;

//     // Ask for confirmation before deleting
//     const confirmDelete = confirm(`Are you sure you want to delete the appointment for ${clientName}'s pet ${petName}?`);
    
//     if (confirmDelete) {
//         // Perform the delete action (e.g., call an API or make a request)
//         // For now, just remove the row from the table
//         row.remove();
        
//         // You can also perform an AJAX request here to delete the data from the server
//         // Example: send a delete request with clientName, petName, or an appointment ID
//     }
// }

// // Ensure the form is initially hidden
// document.addEventListener("DOMContentLoaded", function() {
//     const form = document.getElementById("appointmentForm");
//     form.style.display = "none"; // Make sure form is hidden on page load
// });
