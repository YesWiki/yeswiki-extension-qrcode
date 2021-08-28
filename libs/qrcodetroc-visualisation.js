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
    $.getJSON("?Bazar/json&demand=entries&form=" + canvas.dataset.form + "&query=bf_relation=" + canvas.dataset.relation, function (dataRelations) {
      var data = Object.values(dataRelations)
      // get all bf_titre of entries
      for (var i = 0; i < data.length; i++) {
        entries[data[i]["bf_fiche1"]] = data[i]["bf_fiche1"]
        entries[data[i]["bf_fiche2"]] = data[i]["bf_fiche2"]
      }
      // format relations with bf_titre
      for (var i = 0; i < data.length; i++) {
        relations[i] = new Array()
        var u1 = data[i]["bf_fiche1"]
        relations[i][0] = entries[u1]
        var u2 = data[i]["bf_fiche2"]
        relations[i][1] = entries[u2]
      }
    })
    nbRelations = relations.length
    console.log(entries, relations, nbRelations)
  }

  setInterval(getRelations, canvas.dataset.refresh)

  initVisualisation()
})
