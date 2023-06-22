const btnHome = document.getElementById("home");
btnHome.addEventListener("click", () => {
  window.location = "/index.php";
});

const btnExecute = document.getElementById("execute-btn");
btnExecute.addEventListener("click", function () {
  setInterval(executeScript, 1000);
});

// Function to update the console output
function updateConsoleOutput(output) {
  var consoleOutput = document.getElementById("console-output");
  consoleOutput.innerText += output + "\n";
  consoleOutput.lastElementChild.scrollIntoView();
}

// Function to execute the Python script
function executeScript() {
  var nScheda = document.getElementById("n-scheda").value;

  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        updateConsoleOutput(xhr.responseText);
      }
    }
  };

  xhr.open("POST", "/api/execute_script.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("n_scheda=" + encodeURIComponent(nScheda));
}
