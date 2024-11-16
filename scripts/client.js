
// Toggle visibility of the Add Client Form
function toggleForm() {
    var form = document.getElementById("clientForm");
    if (form.style.display === "none" || form.style.display === "") {
        form.style.display = "block";
    } else {
        form.style.display = "none";
    }
}

// Populate the form with data when editing a client
function populateForm(id, firstName, lastName, email, phone) {
    // Show the form to allow editing
    toggleForm();

    // Populate the form with the selected client's data
    document.getElementById("first_name").value = firstName;
    document.getElementById("last_name").value = lastName;
    document.getElementById("email").value = email;
    document.getElementById("phone").value = phone;

    // Change the form action to update instead of add
    var form = document.querySelector('form');
    var input = document.createElement('input');
    input.type = "hidden";
    input.name = "client_id";
    input.value = id;
    form.appendChild(input);
    
    // Change button text to 'Update Client'
    var submitBtn = form.querySelector('input[type="submit"]');
    submitBtn.value = "Update Client";
    submitBtn.name = "update_client";
}

// Optional: Confirm deletion before submitting the form
function confirmDeletion(clientId) {
    var confirmation = confirm("Are you sure you want to delete this client?");
    if (confirmation) {
        var form = document.createElement('form');
        form.action = "client.php";
        form.method = "POST";
        
        var hiddenInput = document.createElement('input');
        hiddenInput.type = "hidden";
        hiddenInput.name = "client_id";
        hiddenInput.value = clientId;
        form.appendChild(hiddenInput);
        
        var deleteInput = document.createElement('input');
        deleteInput.type = "hidden";
        deleteInput.name = "delete_client";
        form.appendChild(deleteInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

