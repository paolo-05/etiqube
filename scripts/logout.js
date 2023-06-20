const btnLogout = document.getElementById("logout");
btnLogout.addEventListener("click", function () {
  var confirmation = confirm("Sicuro?");
  if (!confirmation) {
    return;
  }
  window.location = "/api/logout.php?action=logout";
});
