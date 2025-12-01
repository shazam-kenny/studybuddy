// Function to confirm before deleting (Client Side Validation)
function confirmDelete() {
    return confirm("Are you sure you want to delete this task? This cannot be undone.");
}

// Function to make alerts fade out automatically
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    if(alerts.length > 0) {
        setTimeout(() => {
            alerts.forEach(el => el.style.display = 'none');
        }, 3000); // Disappears after 3 seconds
    }
});