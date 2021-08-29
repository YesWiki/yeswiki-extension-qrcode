document.addEventListener("DOMContentLoaded", function () {
  // nombre total d'utisateur (pour compter les contacts)
  var nbUsers = new Array()
  // relations avec les données des fiches associées
  var relations = new Array()
  // nombre total de liens
  var nbRelations = new Array()

  var canvas = document.getElementById("canvas-qrcodetroc")

  function initVisualisation() {
    getRelations()
    setTimeout(function () {
      var sourceList = [
        "tools/qrcode/libs/vendor/qrcodetroc.pde",
        "tools/qrcode/libs/vendor/Listeur.pde",
        "tools/qrcode/libs/vendor/Pulseur.pde",
        "tools/qrcode/libs/vendor/Bouton.pde",
      ]

      Processing.loadSketchFromSources(canvas, sourceList)
    }, 200)
  }

  function getRelations() {
    $.getJSON("?api/relations/" + canvas.dataset.relation, function (dataRelations) {
      relations = dataRelations
      nbRelations = Object.keys(relations).length
      console.log(relations, "Nb relations : "+nbRelations)
    })
    $.getJSON("?api/entries/" + canvas.dataset.formuser, function (users) {
      nbUsers = Object.keys(users).length
      console.log("Nb users : "+nbUsers)
    })
  }

  setInterval(getRelations, canvas.dataset.refresh)

  initVisualisation()
})
