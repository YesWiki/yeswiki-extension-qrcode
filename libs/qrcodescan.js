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
  $("#qr-container .step1").removeClass("stepper__row--disabled").addClass("stepper__row--active")
  $("#qr-container .step2").removeClass("stepper__row--active").addClass("stepper__row--disabled")
  $("#qr-container .step3").removeClass("stepper__row--active").addClass("stepper__row--disabled")

  $("#qr-container .paragraph").show()
  $("#qr-container .text-success").html("")
  showNotif($("#qr-container .step1 .paragraph").text(), "alert-info")
  return false
}

// reset qrcode app to step1
$(".btn-reset").click(reset)

// handler of the qrcode data when successfully read
function successHandler(data) {
  // do something when code is read and is a string
  if ((typeof data === "string" || data instanceof String) && data != "undefined") {
    var vcard = parseVcard(data)
    //console.log(vcard);
    if (vcard.fn && vcard.email && vcard.url) {
      if (step == 1) {
        firstpeople = vcard
        step = 2
        $(".step1").removeClass("stepper__row--active").addClass("stepper__row--disabled")

        $(".step2").removeClass("stepper__row--disabled").addClass("stepper__row--active")

        $(".step1 .paragraph").hide()
        $(".step1 .text-success").html("Premier participant : " + vcard.fn)
        showNotif(
          "Vous avez été reconnu comme étant " +
            vcard.fn +
            ". Merci de passer un deuxième Q.R. Code pour faire le lien.",
          "alert-success"
        )
      } else if (step == 2) {
        if (firstpeople.fn == vcard.fn) {
          showNotif(
            "Le premier Q.R. Code et le second sont les mêmes, " + "veuillez utiliser un deuxième Q.R. Code différent.",
            "alert-danger"
          )
        } else {
          secondpeople = vcard
          step = 3

          $(".step2").removeClass("stepper__row--active").addClass("stepper__row--disabled")

          $(".step3").removeClass("stepper__row--disabled").addClass("stepper__row--active")

          $(".step2 .paragraph").hide()
          $(".step2 .text-success").html("Second participant : " + vcard.fn)

          $(".step3 .paragraph").hide()
          $(".step3 .text-success").html(
            "Bravo " + firstpeople.fn + " et " + secondpeople.fn + ", vous êtes maintenant reliés !"
          )

          showNotif(
            "Bravo " +
              firstpeople.fn +
              " et " +
              secondpeople.fn +
              "!! Vous êtes maintenant unis par les liens sacrés du Q.R. code. " +
              "Un email de contact vous a aussi été envoyé.",
            "alert-success"
          )

          // reset after 5 seconds
          setTimeout(reset, 5000)

          // order people by name to have an unique pair in db
          if (firstpeople.fn.toLowerCase() > secondpeople.fn.toLowerCase()) {
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
          params["bf_relation"] = "contact"
          params["bf_fiche1"] = url1[1]
          params["bf_fiche2"] = url2[1]
          params["id_typeannonce"] = "1300"
          $.post("?api/relations", params)

          // first mail send
          var message = "Les informations de votre contact:<br>"
          message = message + firstpeople.fn + "<br>"
          message = message + "Email : " + firstpeople.email[0]["value"][0] + "<br>"
          if (firstpeople.org) {
            message = message + "Organisation : " + firstpeople.org + "<br>"
          }
          if (firstpeople.url) {
            message = message + "Fiche complète : " + firstpeople.url + "<br>"
          }

          $.post("?ContacT/mail", {
            name: firstpeople.fn,
            email: firstpeople.email[0]["value"][0],
            subject: "QRcode contact",
            message: message,
            mail: secondpeople.email[0]["value"][0],
            entete: "Co-construire 2021", //Todo make a param
            type: "contact",
          })

          // second mail send
          var message = "Les informations de votre contact:<br>"
          message = message + secondpeople.fn + "<br>"
          message = message + "Email : " + secondpeople.email[0]["value"][0] + "<br>"
          if (secondpeople.org) {
            message = message + "Organisation : " + secondpeople.org + "<br>"
          }
          if (secondpeople.url) {
            message = message + "Fiche complète : " + secondpeople.url + "<br>"
          }
          $.post("?ContacT/mail", {
            name: secondpeople.fn,
            email: secondpeople.email[0]["value"][0],
            subject: "QRcode contact",
            message: message,
            mail: firstpeople.email[0]["value"][0],
            entete: "Co-construire 2021",
            type: "contact",
          })
        }
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
const config = { fps: 10, qrbox: 300 }
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
