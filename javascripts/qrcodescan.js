// parse string in vcard format
function parseVcard(input) {
  var Re1 = /^(version|fn|title|org|url|adr):(.+)$/i
  var Re2 = /^([^:;]+);([^:]+):(.+)$/
  var ReKey = /item\d{1,2}\./
  var fields = {}

  input.split(/\r\n|\r|\n/).forEach(function (line) {
    var results, key

    if (Re1.test(line)) {
      results = line.match(Re1)
      key = results[1].toLowerCase()
      fields[key] = results[2]
    } else if (Re2.test(line)) {
      results = line.match(Re2)
      key = results[1].replace(ReKey, "").toLowerCase()

      var meta = {}
      results[2]
        .split(";")
        .map(function (p, i) {
          var match = p.match(/([a-z]+)=(.*)/i)
          if (match) {
            return [match[1], match[2]]
          } else {
            return ["TYPE" + (i === 0 ? "" : i), p]
          }
        })
        .forEach(function (p) {
          meta[p[0]] = p[1]
        })

      if (!fields[key]) fields[key] = []

      fields[key].push({
        meta: meta,
        value: results[3].split(";"),
      })
    }
  })
  return fields
}

// do speech synthesis of the text inside the selector
function speak(selector) {
  // cut former speech
  window.speechSynthesis.cancel()
  //
  var to_speak = new SpeechSynthesisUtterance($(selector).text())
  window.speechSynthesis.speak(to_speak)
}

// clean notification zone and add new notification
function showNotif(txt, alertclass) {
  $("#qrinfos .alert")
    .removeClass("alert-danger")
    .removeClass("alert-info")
    .removeClass("alert-success")
    .addClass(alertclass)
    .html(txt)
  return
}

function reset() {
  step = 1
  lastResult = 0
  $("#qr-container .step1").removeClass("stepper__row--disabled").addClass("stepper__row--active")
  $("#qr-container .step2").removeClass("stepper__row--active"  ).addClass("stepper__row--disabled")
  $("#qr-container .step3").removeClass("stepper__row--active"  ).addClass("stepper__row--disabled")

  $("#qr-container .paragraph").show()
  $("#qr-container .text-success").html("")
  showNotif($("#qr-container .step1 .paragraph").text(), "alert-info")
  return false
}

// reset qrcode app to step1
$(".btn-reset").on('click', reset)

function stepHandler(step, entry) {
  if (step == 1) {
    firstpeople = entry
    step = 2
    $(".step1").removeClass("stepper__row--active").addClass("stepper__row--disabled")

    $(".step2").removeClass("stepper__row--disabled").addClass("stepper__row--active")

    $(".step1 .paragraph").hide()
    $(".step1 .text-success").html("Premier participant : " + entry.bf_titre)
    showNotif(
      "Vous avez été reconnu comme étant " +
      entry.bf_titre +
        ". Merci de passer un deuxième Q.R. Code pour faire le lien.",
      "alert-success"
    )
  } else if (step == 2) {
    if (firstpeople.fn == entry.bf_titre) {
      showNotif(
        "Le premier Q.R. Code et le second sont les mêmes, " + "veuillez utiliser un deuxième Q.R. Code différent.",
        "alert-danger"
      )
    } else {
      secondpeople = entry
      step = 3

      $(".step2").removeClass("stepper__row--active").addClass("stepper__row--disabled")

      $(".step3").removeClass("stepper__row--disabled").addClass("stepper__row--active")

      $(".step2 .paragraph").hide()
      $(".step2 .text-success").html("Second participant : " + entry.bf_titre)

      $(".step3 .paragraph").hide()
      $(".step3 .text-success").html(
        "Bravo " + firstpeople.bf_titre + " et " + secondpeople.bf_titre + ", vous êtes maintenant reliés !"
      )

      showNotif(
        "Bravo " +
          firstpeople.bf_titre +
          " et " +
          secondpeople.bf_titre +
          "!! Vous êtes unis par les liens sacrés du Q.R. code. " +
          "Un email de contact vous a été envoyé.",
        "alert-success"
      )

      // reset after 10 seconds
      setTimeout(reset, 10000)

      // order people by name to have an unique pair in db
      if (firstpeople.bf_titre.toLowerCase() > secondpeople.bf_titre.toLowerCase()) {
        var temp = secondpeople
        secondpeople = firstpeople
        firstpeople = temp
      }
      // TODO test if relation already exists
      var url1 = firstpeople.url.split("?")
      var url2 = secondpeople.url.split("?")
      // make link in database
      var params = new Object()
      params["bf_titre"] = 'Relation "{{bf_relation}}" entre {{bf_fiche1}} et {{bf_fiche2}}'
      params["bf_relation"] = qrinfos.dataset.relation
      params["bf_fiche1"] = url1[1]
      params["bf_fiche2"] = url2[1]
      params["id_typeannonce"] = "1300"
      $.post("?api/relations", params)

      // first mail send
      var message = "Les informations de votre contact:\n"
      message = message + firstpeople.bf_titre + "\n"
      message = message + "Email : " + firstpeople.bf_mail + "\n"
      if (firstpeople.org) {
        message = message + "Organisation : " + firstpeople.org + "\n"
      }
      if (firstpeople.url) {
        message = message + "Fiche complète : " + firstpeople.url + "\n"
      }

      $.post("?ContacT/mail", {
        name: firstpeople.bf_titre,
        email: firstpeople.bf_mail,
        subject: "QRcode contact",
        message: message,
        mail: secondpeople.bf_mail,
        entete: "Co-construire 2023", //Todo make a param
        type: "contact",
      })

      // second mail send
      var message = "Les informations de votre contact:\n"
      message = message + secondpeople.bf_titre + "\n"
      message = message + "Email : " + secondpeople.bf_mail + "\n"
      if (secondpeople.org) {
        message = message + "Organisation : " + secondpeople.org + "\n"
      }
      if (secondpeople.url) {
        message = message + "Fiche complète : " + secondpeople.url + "\n"
      }
      $.post("?ContacT/mail", {
        name: secondpeople.bf_titre,
        email: secondpeople.bf_mail,
        subject: "QRcode contact",
        message: message,
        mail: firstpeople.bf_mail,
        entete: "Co-construire 2023",
        type: "contact",
      })
    }
  }
  return step;
}

// handler of the qrcode data when successfully read
function successHandler(data) {
  var qrinfos = document.getElementById("qrinfos")
  // do something when code is read and is a string
  if ((typeof data === "string" || data instanceof String) && data != "undefined") {
    var vcard = parseVcard(data)
    if (vcard.fn && vcard.email && vcard.url) {
      vcard.bf_titre = vcard.fn
      vcard.bf_mail = vcard.email[0]["value"][0]
      vcard.bf_structure = vcard.org
      step = stepHandler(step, vcard)
    } else {
      var entry = data.split('personne=');
      if (entry[1]) {
        var req = new XMLHttpRequest();
        req.onreadystatechange = function() {
          if (req.readyState === 4) {
            var response = req.responseText;
            var json = JSON.parse(response);
            json.url = data.split('/?')[0]+'/?'+json.id_fiche;
            step = stepHandler(step, json)
            console.log(json)
          }
        };
        req.open('GET', '?'+entry[1]+'/raw');
        req.send(null);
      }
    }
  }
}

// the qrcode couldn't be read
function errorHandler(error) {
  //show read errors (often )
  // showNotif('Erreur de lecture du Q.R. code.', 'alert-danger');
}

// the video stream could be opened
function videoErrorHandler(videoError) {
  showNotif("La capture vidéo n'a pas pu démarrer, utilisation du scanner impossible.", "alert-danger")
}

// global vars for the scanner
var step = 1,
  firstpeople,
  secondpeople

var resultContainer = document.getElementById("qrreader-results")
var lastResult,
  countResults = 0

// This method will trigger user permissions
const html5QrCode = new Html5Qrcode(/* element id */ "qrreader", { formatsToSupport: [ Html5QrcodeSupportedFormats.QR_CODE ] })
const config = { fps: 20, qrbox: 250 }
const qrCodeSuccessCallback = (decodedText, decodedResult) => {
  //console.info(decodedText, decodedResult)
  if (decodedText !== lastResult) {
    lastResult = decodedText;
    successHandler(decodedText)
  }
}
// we prefer back camera for scanning from mobile phone
html5QrCode
  .start({ facingMode: "environment" }, config, qrCodeSuccessCallback, (errorMessage) => {
    // parse error, ignore it.
    // console.error(errorMessage)
  })
  .catch((err) => {
    // Start failed, handle it.
    console.error(err)
  })

document.addEventListener("DOMContentLoaded", function (event) {
  var qrinfos = document.getElementById("qrinfos")
  var entity = JSON.parse(qrinfos.dataset.entity);
  if (entity && Object.keys(entity).length > 0) {
    firstpeople = entity
        step = 2
        $(".step1").removeClass("stepper__row--active").addClass("stepper__row--disabled")

        $(".step2").removeClass("stepper__row--disabled").addClass("stepper__row--active")

        $(".step1 .paragraph").hide()
        $(".step1 .text-success").html("Premier participant : " + entity.bf_titre)
        showNotif(
          "Vous avez été reconnu comme étant " +
          entity.bf_titre +
            ". Merci de passer un deuxième Q.R. Code pour faire le lien.",
          "alert-success"
        )
  }
  if (qrinfos.dataset.speak === "true") {
    function mutate(mutations) {
      mutations.forEach(function (mutation) {
        // console.log(mutation.type)
        speak("#qrinfos .alert")
      })
    }

    var target = document.querySelector("div#qrinfos .alert")
    var observer = new MutationObserver(mutate)
    var config = { characterData: false, attributes: false, childList: true, subtree: false }
    observer.observe(target, config)

    // first load
    speak("#qrinfos .alert")
  }
})
