document.addEventListener("DOMContentLoaded", function (event) {
  // titre des fiches
  var entries = new Array()

  // récupération des liens
  var relations = new Array()

  // stockage des nombres de liens
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
    $.getJSON("?api/relations&query=bf_relation=" + canvas.dataset.relation, function (dataRelations) {
      nbRelations = relations.length
      console.log(dataRelations, nbRelations)
    })
  }

  setInterval(getRelations, canvas.dataset.refresh)

  initVisualisation()
})
