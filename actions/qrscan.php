<?php
$this->AddJavascriptFile('tools/qrcode/libs/jsqrcode-combined.min.js');
$this->AddJavascriptFile('tools/qrcode/libs/html5-qrcode.min.js');
$this->AddJavascriptFile('tools/qrcode/libs/qrcodescan.js');
$this->AddCSSFile('tools/qrcode/presentation/styles/qrcodescan.css');
$idlinks = $this->getParameter('idlinks');
if (empty($idlinks)) {
    echo '<div class="alert alert-danger"><strong>Action qrscan :</strong> '._t('QR_MISSING_PARAM_IDLINKS').'</div>';
    return;
}
$idusers = $this->getParameter('idusers');
if (empty($idusers)) {
    echo '<div class="alert alert-danger"><strong>Action qrscan :</strong> '._t('QR_MISSING_PARAM_IDUSERS').'</div>';
    return;
}

$speak = $this->getParameter('speak');
if ($speak == '0' or $speak == 'false' or $speak == 'no') {
    $speak = false;
} else {
    $speak = true;
}
if ($speak) {
    $this->AddJavascript('
    function mutate(mutations) {
      mutations.forEach(function(mutation) {
        console.log(mutation.type);
        speak("#qrinfos .alert");
      });
    }

    var target = document.querySelector("div#qrinfos .alert")
    var observer = new MutationObserver( mutate );
    var config = { characterData: false, attributes: false, childList: true, subtree: false };
    observer.observe(target, config);

    // first load
    speak("#qrinfos .alert");
    ');
}
$output = '<div id="qrinfos" class="hide">
<div class="alert alert-info">'._t('QR_FIRST_INSTRUCTIONS').'</div>
</div>
<div id="qr-container">
<div class="row">
<div class="col-md-8">
<div class="flex-center">
<div class="flex-element text-center">
<div id="qrreader" data-idlinks="'.$idlinks.'" data-idusers="'.$idusers.'"></div>
<a href="#" class="btn btn-default btn-reset"><i class="glyphicon glyphicon-refresh"></i> '._t('QR_RESET').'</a>
</div>
</div>
</div>
<div class="col-md-4">
<div class="step1 stepper__row stepper__row--active">
    <div class="stepper--vertical">
      <div class="stepper--vertical__circle">
        <span class="stepper--vertical__circle__text">
          1
        </span>
      </div>
      <div class="stepper--vertical__details">
        <h3 class="heading__three">
          '._t('QR_SCAN_FIRST').'

        </h3>
        <p class="text-success"></p>
        <p class="paragraph">
          '._t('QR_FIRST_INSTRUCTIONS').'
        </p>
      </div>
    </div>
  </div>
  <div class="step2 stepper__row stepper__row--disabled">
    <div class="stepper--vertical">
      <div class="stepper--vertical__circle">
        <span class="stepper--vertical__circle__text">
          2
        </span>
      </div>
      <div class="stepper--vertical__details">
        <h3 class="heading__three">
          '._t('QR_SCAN_SECOND').'
        </h3>
        <p class="text-success"></p>
        <p class="paragraph">
          '._t('QR_SECOND_INSTRUCTIONS').'
        </p>
      </div>
    </div>
  </div>
  <div class="step3 stepper__row stepper__row--disabled">
    <div class="stepper--vertical">
      <div class="stepper--vertical__circle">
        <span class="stepper--vertical__circle__text">
          3
        </span>
      </div>
      <div class="stepper--vertical__details">
        <h3 class="heading__three">
          '._t('QR_FINISH').'
        </h3>
        <p class="text-success"></p>
        <p class="paragraph">
          '._t('QR_FINAL_INSTRUCTIONS').'
        </p>
      </div>
    </div>
  </div>
</div>
</div>
</div>'."\n";
echo $output;
