// assets/js/main.js
document.addEventListener("DOMContentLoaded", () => {
  // Confirm delete/archive actions
  const deleteForms = document.querySelectorAll(".delete-form");
  deleteForms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      if (!confirm("Are you sure you want to archive this announcement?")) {
        e.preventDefault();
      }
    });
  });

  // Auto-dismiss Bootstrap alerts after 3 seconds if any exist
  const alerts = document.querySelectorAll(".alert");
  alerts.forEach((alert) => {
    setTimeout(() => {
      const bsAlert = new bootstrap.Alert(alert);
      bsAlert.close();
    }, 3000);
  });
});
