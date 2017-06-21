<head>
  <title>Space Sat Eridanus</title>
  <html lang="fr">
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Les différents satellites artificiels et les agences qui les envoient dans l’espace." />
  <meta name="keywords" content="satellite, artificiel, agence, station, spatiale, espace, ariane, nasa, space x, télécommunication, lanceur, fusée, spatiale,
    orbite, satellisation, géolocalisation, téléscope, astronautique, ISS, sonde, blue origin " />
  
  <!-- css -->
  <?php $link_css = (isset($access)&&$access=='admin'?'../':'').'assets/css/main.css';
        $link_css .= '?v='.filemtime($link_css);
  ?>
  <link rel="stylesheet" href="<?php echo $link_css; ?>" />
    <!-- thickbox -->
  <link rel="stylesheet" href="<?php echo (isset($access)&&$access=='admin'?'../':''); ?>assets/css/thickbox.css" type="text/css" media="screen" />

  <!-- icones -->
  <link rel="apple-touch-icon" sizes="180x180" href="./images/favicons/android-chrome-192x192.png">
  <link rel="icon" type=".image/png" sizes="32x32" href="./images/favicons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="favicon-32x32.png/favicon-16x16.png">
  <!--<link rel="manifest" href="/manifest.json">-->
  <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
  <meta name="theme-color" content="#ffffff">
    
    <!-- Js validation formulaire page contact -->
  <?php $url = explode('/',$_SERVER["SCRIPT_NAME"]); //ESSAYER basename
  if($url[count($url)-1]=="contact.php"||(isset($access)&&$access=='admin')) { 
      /* Js validation formulaire page contact */
      $link_js = (isset($access)&&$access=='admin'?'../':'').'assets/js/validationFormulaire.js';
      $link_js .= '?v='.filemtime($link_js);  ?>
      <script type="text/javascript" src="<?php echo $link_js; ?>"></script>
      <!-- Js tynimce (wiziwig) -->
      <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=d9gev28oiz7icwy2ev18240rkedm4esg5rpdes2c25msxce5"></script>
      <script>tinymce.init({ selector:'textarea' <?php if((isset($access)&&$access=='admin')) { ?> ,
        height: 500,
        theme: 'modern',
        plugins: [
          'advlist autolink lists link image charmap print preview hr anchor pagebreak',
          'searchreplace wordcount visualblocks visualchars code fullscreen',
          'insertdatetime media nonbreaking save table contextmenu directionality',
          'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
        ],
        toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        toolbar2: 'print preview media | forecolor backcolor emoticons | codesample help',
        image_advtab: true,
        templates: [
          { title: 'Test template 1', content: 'Test 1' },
          { title: 'Test template 2', content: 'Test 2' }
        ],
        content_css: [
          '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
          '//www.tinymce.com/css/codepen.min.css'
        ] <?php } ?>
       });</script>
  <?php } ?>



</head>
