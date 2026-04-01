// assets/js/main.js

// FIXED: UI toggle now remembers user preference across page refreshes
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

  // Save preference to browser storage
  localStorage.setItem("cpe_preferred_view", view);
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

// Modal Data Filler Logic
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

// Global Event Listeners
document.addEventListener("DOMContentLoaded", () => {
  // 1. Restore the user's preferred view (Card or Calendar)
  const viewCardEl = document.getElementById("view-card");
  if (viewCardEl) {
    // Only run this on the public index page
    const preferredView = localStorage.getItem("cpe_preferred_view") || "card";
    toggleView(preferredView);
  }

  // 2. Safe Delete Confirmation Prompts
  document.querySelectorAll(".delete-form").forEach((form) => {
    form.addEventListener("submit", function (e) {
      const action = this.querySelector('input[name="action"]').value;
      let msg = "Are you sure you want to proceed?";

      if (action === "archive")
        msg = "Archive this item? It will be hidden from the public.";
      if (action === "hard_delete")
        msg =
          "WARNING: PERMANENTLY delete this? This cannot be undone and will be logged.";

      if (!confirm(msg)) e.preventDefault();
    });
  });

  // 3. Auto-dismiss Bootstrap Alerts after 3.5 seconds
  const alerts = document.querySelectorAll(".alert");
  alerts.forEach((alert) => {
    setTimeout(() => {
      const bsAlert = new bootstrap.Alert(alert);
      bsAlert.close();
    }, 3500);
  });
});
