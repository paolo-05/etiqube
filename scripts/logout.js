const btnLogout = document.getElementById("logout");
btnLogout.addEventListener("click", function () {
  var confirmationModal = new bootstrap.Modal(
    document.getElementById("confirmationModal")
  );
  confirmationModal.show();
});

document.getElementById("confirmLogout").addEventListener("click", function () {
  window.location = "/api/logout.php?action=logout";
});
