const fps = 20;
const scannerSize = 200;

// This method will trigger user permissions
const html5QrCode = new Html5Qrcode("qrreader", {
  formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE],
});
const config = { fps: fps, qrbox: scannerSize };
let lastResult = "";

const qrCodeSuccessCallback = (decodedText, decodedResult) => {
  // console.info(decodedText, decodedResult);
  if (decodedText !== lastResult) {
    lastResult = decodedText;
    successHandler(decodedText);
  }
};
// we prefer back camera for scanning from mobile phone
html5QrCode
  .start(
    { facingMode: "environment" },
    config,
    qrCodeSuccessCallback,
    (errorMessage) => {
      // parse error, ignore it.
      // console.error(errorMessage);
    },
  )
  .catch((err) => {
    // Start failed, handle it.
    console.error(err);
  });

// do speech synthesis of the text inside the selector
function speak(selector) {
  // cut former speech
  window.speechSynthesis.cancel();
  //
  var to_speak = new SpeechSynthesisUtterance($(selector).text());
  window.speechSynthesis.speak(to_speak);
}

// clean notification zone and add new notification
function showNotif(txt, alertclass) {
  $("#qrinfos .alert")
    .removeClass("alert-danger")
    .removeClass("alert-info")
    .removeClass("alert-success")
    .addClass(alertclass)
    .html(txt);
  return;
}

function isValidHttpUrl(string) {
  let url;

  try {
    url = new URL(string);
  } catch (_) {
    return false;
  }

  return url.protocol === "http:" || url.protocol === "https:";
}

// handler of the qrcode data when successfully read
function successHandler(data) {
  var url = new URL(data);
  if (
    (typeof data === "string" || data instanceof String) &&
    data != "undefined" &&
    isValidHttpUrl(data)
  ) {
    $.ajax({
      url: url.href,
      headers: { Accept: "application/json" },
    }).done(function (data) {
      console.log(data);
      // resource card with linked media file
      if (data.listeListeTypeCarte == "③" && data.fichierbf_file) {
        let song = url.origin + "/files/" + data.fichierbf_file;
        console.log(song); // TODO test if url exists
        $("#multimedia-player").html("");
        $("#multimedia-player").html(
          "<figure><figcaption><strong>En écoute : " +
            data.bf_titre +
            '</strong></figcaption><audio id="audio-player" controls autoplay autobuffer src="' +
            song +
            '"></audio><a style="display:block" download href="' +
            song +
            '"><i class="fas fa-download"></i> Télécharger</a></figure>',
        );
        $("#multimedia-playlist .active").removeClass("active");
        $("#multimedia-playlist").append(
          '<button type="button" class="song list-group-item active" data-url="' +
            song +
            '"><i class="fa fa-music"></i> ' +
            data.bf_titre +
            "</button>",
        );
      }
    });
  }
}

// the qrcode couldn't be read
function errorHandler(error) {
  //show read errors (often )
  // showNotif('Erreur de lecture du Q.R. code.', 'alert-danger');
}

// the video stream could be opened
function videoErrorHandler(videoError) {
  showNotif(
    "La capture vidéo n'a pas pu démarrer, utilisation du scanner impossible.",
    "alert-danger",
  );
}

document.addEventListener("DOMContentLoaded", function (event) {
  if (qrinfos.dataset.speak === "true") {
    function mutate(mutations) {
      mutations.forEach(function (mutation) {
        // console.log(mutation.type)
        speak("#qrinfos .alert");
      });
    }

    var target = document.querySelector("div#qrinfos .alert");
    var observer = new MutationObserver(mutate);
    var config = {
      characterData: false,
      attributes: false,
      childList: true,
      subtree: false,
    };
    observer.observe(target, config);

    // first load
    speak("#qrinfos .alert");

    // clic on playlist item
    $("#multimedia-playlist").on("click", ".song", function () {
      alert("coucou");
    });
  }
});
