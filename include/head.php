<head>
  <title>Space Sat Eridanus</title>
  <html lang="fr">
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <?php $link_css = (isset($access)&&$access=='admin'?'../':'').'assets/css/main.css';
        $link_css .= '?v='.filemtime($link_css);
  ?>
  <link rel="stylesheet" href="<?php echo $link_css; ?>" />

  <link rel="apple-touch-icon" sizes="180x180" href="./images/favicons/android-chrome-192x192.png">
  <link rel="icon" type=".image/png" sizes="32x32" href="./images/favicons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="favicon-32x32.png/favicon-16x16.png">
  <!--<link rel="manifest" href="/manifest.json">-->
  <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
  <meta name="theme-color" content="#ffffff">
    <!-- Js validation formulaire page contact -->
  <?php
  $url = explode('/',$_SERVER["SCRIPT_NAME"]); //ESSAYER basename
  if($url[count($url)-1]=="contact.php"||(isset($access)&&$access=='admin')) { 
      $link_js = (isset($access)&&$access=='admin'?'../':'').'assets/js/validationFormulaire.js';
      $link_js .= '?v='.filemtime($link_js);  ?>
      <script type="text/javascript" src="<?php echo $link_js; ?>"></script>
  <?php } ?>
</head>
