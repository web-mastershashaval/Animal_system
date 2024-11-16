document.addEventListener("DOMContentLoaded", function () {
    const toggleButton = document.querySelector('.toggle-button');
    const formContainer = document.getElementById('productForm');

    function toggleForm() {
        if (formContainer) {
            formContainer.style.display = formContainer.style.display === 'none' || formContainer.style.display === '' ? 'block' : 'none';
        } else {
            console.error("Form container with ID 'productForm' not found.");
        }
    }

    if (toggleButton) {
        toggleButton.addEventListener('click', toggleForm);
    }
});
