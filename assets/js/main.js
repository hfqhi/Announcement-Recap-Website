// assets/js/main.js

// FIXED: Using 'block' instead of 'flex' stops the scrambled UI bug
function toggleView(view) {
  document.getElementById("view-card").style.display =
    view === "card" ? "block" : "none";
  document.getElementById("view-calendar").style.display =
    view === "calendar" ? "block" : "none";
  document
    .getElementById("btn-card")
    .classList.toggle("active", view === "card");
  document
    .getElementById("btn-calendar")
    .classList.toggle("active", view === "calendar");
}

// Live Real-Time Clock
setInterval(() => {
  const clock = document.getElementById("liveClock");
  if (clock) {
    clock.innerText = new Date().toLocaleTimeString("en-US", {
      hour12: true,
      hour: "numeric",
      minute: "2-digit",
      second: "2-digit",
    });
  }
}, 1000);

// Modal and Confirmation Logic
function openSubjectModal(action, data = null) {
  document.getElementById("modalAction").value = action;
  document.getElementById("modalTitle").innerText =
    action === "add" ? "New Subject" : "Edit Subject";
  document.getElementById("modalId").value = data ? data.id : "";
  document.getElementById("modalCode").value = data ? data.code : "";
  document.getElementById("modalName").value = data ? data.name : "";
  document.getElementById("modalProf").value = data ? data.professor : "";
  document.getElementById("modalSched").value = data ? data.schedule : "";
  document.getElementById("modalTheme").value = data
    ? data.color_theme
    : "bg-other";
  new bootstrap.Modal(document.getElementById("subjectModal")).show();
}

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".delete-form").forEach((form) => {
    form.addEventListener("submit", function (e) {
      const action = this.querySelector('input[name="action"]').value;
      let msg = "Are you sure?";
      if (action === "archive") msg = "Archive this item?";
      if (action === "hard_delete")
        msg = "PERMANENTLY delete this? This cannot be undone.";
      if (!confirm(msg)) e.preventDefault();
    });
  });

  const alerts = document.querySelectorAll(".alert");
  alerts.forEach((alert) => {
    setTimeout(() => {
      const bsAlert = new bootstrap.Alert(alert);
      bsAlert.close();
    }, 3500);
  });
});
