<!doctype html>
<html>
  <?php
  ini_set ( 'max_execution_time', '0' );
  $page = "";

  $file_path = $_SERVER['DOCUMENT_ROOT'].'/config.php';
  if ( !file_exists($file_path) ) {
    echo("Config file config.php not found. $file_path");
  }
  $paths = array();
  if ( file_exists($file_path) ) {
    $configfile = fopen($file_path, "r");
    while (!feof($configfile)) {
      $line = fgets($configfile);
      $line=rtrim($line);
      if ($line != ""){
        $splitline = explode(':', $line);
        $paths[$splitline[0]]=isset($splitline[1]) ? $splitline[1] : null;
      }
    }
  }
  $USER_MODE = $paths['USER_MODE'];

  //$USER_MODE = "debug";
  if ($_GET) {
    $page = $_GET ['page'];
  }

  //CONNECT SQL
  include ($_SERVER['DOCUMENT_ROOT']."/unilectin3D/includes/connect.php");
  $connexion = connectdatabase();
  include ($_SERVER['DOCUMENT_ROOT']."/predict/includes/connect.php");
  $connexionBIG = connectdatabaseBIG();

  // echo '<pre>'; print_r($array); echo '</pre>';
  ?>

  <head>
    <!--<meta name="robots" value="noindex" />-->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="viewport" content="width=500">

    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
    <!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico" /><![endif]-->

    <script src="/js/jquery-2.2.2.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>

    <link rel="stylesheet" media="screen" type="text/css" href="/css/bootstrap.min.css?v=156168496152">
    <link rel="stylesheet" media="screen" type="text/css" href="/css/bootstrap-theme.min.css?v=156168496152">
    <link rel="stylesheet" media="screen" type="text/css" href="/css/header.css?v=156168496152">
    <link rel="stylesheet" media="screen" type="text/css" href="/css/button.css?v=156168496152">
    <link rel="stylesheet" media="screen" type="text/css" href="/css/div.css?v=156168496152">
    <link rel="stylesheet" media="screen" type="text/css" href="/css/element.css?v=156168496152">
    <link rel="stylesheet" media="screen" type="text/css" href="/css/input.css?v=156168496152">
    <link rel="stylesheet" media="screen" type="text/css" href="/css/table.css?v=156168496152">
    <link rel="stylesheet" media="screen" type="text/css" href="/css/slider.css?v=156168496152">
    <link rel="stylesheet" media="screen" type="text/css" href="/css/range.css?v=156168496152">

    <meta http-equiv="Content-Type" content="text/html" charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=11; IE=9; IE=8; IE=7" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />

      <!-- Matomo -->
      <script type="text/javascript">
          var _paq = window._paq = window._paq || [];
          /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
          _paq.push(['trackPageView']);
          _paq.push(['enableLinkTracking']);
          (function() {
              var u="//unilectin.eu/matomo/";
              _paq.push(['setTrackerUrl', u+'matomo.php']);
              _paq.push(['setSiteId', '1']);
              var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
              g.type='text/javascript'; g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
          })();
      </script>
      <!-- End Matomo Code -->

      <!-- Google Analytics -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=UA-113302841-1"></script>
      <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-113302841-1');
      </script>
      <!-- End Google Analytics -->

    <!-- Search Console -->
    <script>
        function search_keyword(keyword){
            if(keyword==''){return;}
            $("#keyword_search_content").html("<div class='div-border' style='color:black;background-color:lightgrey;margin-bottom:5px;'>Searching ...</div>");
            $.get("/pages/keyword_search.php?keyword="+keyword, function(data, status){
                $('#keyword_search_content').html(data);
            });
        }
    </script>
  </head>

  <div id="header" style='display:block;margin: 0px;overflow-x:auto;'>
    <div id="header-links" style='display:block;width:1000px;margin-top: 0px;margin-right: auto;margin-bottom: 0px;margin-left: auto;padding:5px;'>
        <a class="header_main" style="padding: 0px;" href="/unilectin3D">UniLectin3D</a>
        <a class="btn-lg btn-primary" style="vertical-align: middle;" href="/unilectin3D/search">Search by field</a>
        <a class="btn-lg btn-primary" style="vertical-align: middle;" href="/unilectin3D/tutorial">Tutorial</a>
        <a class="btn-lg btn-primary" style="vertical-align: middle;" href="/pages/about">About</a>
        <a class="btn-lg btn-primary" style="vertical-align: middle;" href="/pages/contact"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></a>
        <a class="btn-lg btn-primary" style="vertical-align: middle;" href="/admin_pages/index.php"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></a>
        <div style="vertical-align: middle;display: inline-flex;margin:0px;margin-right:10px;vertical-align:center;width: 200px;height: 40px;background-color: #24292e;color: #4c4c4c;border:1px solid black;">
          <input placeholder="Search" type='text' id='search_lectin' name="search_lectin" style="font-size:26px;width: 80%; height: 40px;padding-top:5px;margin:0;border:none;" onkeydown="if (event.keyCode == 13) { search_keyword($('#search_lectin').val()); }">
          <button class="btn-lg btn-primary" style="width:20%;height:40px;margin:0;padding:2px;font-size:25px;float:right;border:none;border-radius:5px;"  onclick="search_keyword($('#search_lectin').val());"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
        <a class="header_main" style="padding: 0px;" href='/'>UniLectin</a>
        <div id="keyword_search_content" style='max-height:200px;overflow-y:auto;'></div>
      </div>
      </div>

    <div id="content_scroll" style='display:block;overflow-y:auto;'>
      <div id="content" style='display:block;max-width:1000px;margin-top: 0px;margin-right: auto;margin-bottom: 0px;margin-left: auto;'>
        <div id="content-top-margin" style='width:100%;height:0px;padding:15px;'></div>
        <div id="content-main" style='width:100%;margin:0;padding:0;display:inline-block;'>