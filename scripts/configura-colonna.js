const n_serraturaInput = document.getElementById("n_serratura");
n_serraturaInput.addEventListener("change", function () {
  const n_serraturaHidden = document.getElementById("n_serratura-hidden");
  n_serraturaHidden.value = this.value;
});

const btnConcludi = document.getElementById("btn-concludi");
const slotRimanenti = document.getElementById("slot_rimanenti").value;
if (slotRimanenti == 0) {
  btnConcludi.disabled = false;
  btnConcludi.addEventListener("click", function () {
    const nScheda = document.querySelector("input[name=n_scheda]");
    if (nScheda.value < 2) {
      btnConcludi.disabled = true;
      return alert("Il numero minimo di colonne è 2!");
    }

    const vanoTecnicoInserito = window.localStorage.getItem("vano-inserito");
    if (!vanoTecnicoInserito) {
      btnConcludi.disabled = true;
      return alert("Inserisci vano tecnico!");
    }

    var confirmation = confirm("Concludere la configurazione?");
    if (!confirmation) {
      return;
    }
    alert("Configurazione salvata con successo!");
    window.location = "/index.php";
  });
}

const deleteConfigurationButton = document.getElementById(
  "deleteConfiguration"
);
deleteConfigurationButton.addEventListener("click", function (event) {
  var confirmation = confirm(
    "Sicuro di voler cancellare la configurazione? Questa operazione non può essere ripristinata."
  );
  if (!confirmation) {
    return;
  }
  var deleteUrl =
    "http://127.0.0.1:8080/api/db_connection.php?action=deleteCofiguration";

  var xhr = new XMLHttpRequest();
  xhr.open("POST", deleteUrl, true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      alert("Configurazione cancellata con successo!");

      confirmation = confirm("Ricominciare la configurazione?");
      if (confirmation) {
        window.location = "configura-colonna.php";
      } else {
        window.location = "/index.php";
      }
    }
  };
  xhr.send();
});

const btnHome = document.getElementById("home");
btnHome.disabled = false;
btnHome.addEventListener("click", function () {
  const nSportello = document.getElementById("n_sportello").value;
  if (nSportello != 1) {
    btnHome.disabled = true;
    return alert("Configurazione in corso, concludila o cancellala.");
  }
  window.location = "/index.php";
});
