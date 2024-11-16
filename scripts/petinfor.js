// Toggle visibility of forms
function toggleForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    } else {
        console.error(`Form with ID ${formId} not found.`);
    }
}

// Populate Update Form with existing data for editing
function populateUpdateForm(petId, ownerName, petName, petType, petImage, petBreed, petAge) {
    // Make the update form visible
    toggleForm('updateForm');

    // Populate the form fields
    document.getElementById('update_pet_id').value = petId;
    document.getElementById('update_owner_name').value = ownerName;
    document.getElementById('update_pet_name').value = petName;
    document.getElementById('update_pet_type').value = petType;
    document.getElementById('update_pet_image').value = petImage;
    document.getElementById('update_pet_breed').value = petBreed;
    document.getElementById('update_pet_age').value = petAge;
}

// Confirmation before deleting a pet
function confirmDelete() {
    return confirm("Are you sure you want to delete this pet?");
}

// Ensure forms are hidden by default
document.addEventListener("DOMContentLoaded", function () {
    const addForm = document.getElementById('addForm');
    const updateForm = document.getElementById('updateForm');

    // Check if elements exist before accessing their properties
    if (addForm) addForm.style.display = 'none';
    if (updateForm) updateForm.style.display = 'none';
});
