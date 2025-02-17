<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--<link rel="stylesheet" type="text/css" href="/unilectin3D/swissmodel/bootstrap-3.css" media="screen,print">-->
    <!--<link rel="stylesheet" type="text/css" href="/unilectin3D/swissmodel/bootstrap-theme-3.css" media="screen,print">-->
    <link rel="stylesheet" media="screen" type="text/css" href="/css/bootstrap.min.css?v=156168496152">
    <link rel="stylesheet" media="screen" type="text/css" href="/css/bootstrap-theme.min.css?v=156168496152">
    <link rel="stylesheet" type="text/css" href="/unilectin3D/swissmodel/master.css" media="all">
    <link rel="stylesheet" type="text/css" href="/unilectin3D/swissmodel/jquery.css">
    <link rel="stylesheet" type="text/css" href="/unilectin3D/swissmodel/jquery-ui.css">
    <style>.ligandInput input, select {font-size: .8em;}</style>
    <script src="/unilectin3D/swissmodel/jquery-3.js"></script>
    <script src="/unilectin3D/swissmodel/jquery-ui.js"></script>
    <script src="/unilectin3D/swissmodel/bootstrap-3.js"></script>
    <script src="/unilectin3D/swissmodel/jquery_002.js"></script>
    <script src="/unilectin3D/swissmodel/jquery.js"></script>
    <script index.html="" src="/unilectin3D/swissmodel/jquery_003.js"></script>
    <script index.html="" src="/unilectin3D/swissmodel/bio-pv.js"></script>
    <script index.html="" src="/unilectin3D/swissmodel/ngl.js"></script>
    <script index.html="" src="/unilectin3D/swissmodel/polyfill.js"></script>
    <script index.html="" src="/unilectin3D/swissmodel/structure.js"></script>
    <script index.html="" src="/unilectin3D/swissmodel/alignment.js"></script>
    <script index.html="" src="/unilectin3D/swissmodel/raphael-min.js"></script>
    <script index.html="" src="/unilectin3D/swissmodel/svgtocanvas.js"></script>
</head>
<body>

<?php
$pdb=strtolower($_GET['pdb']);
$upload_dir = "../../templates/html/";
$file = $upload_dir."$pdb.html";
if(file_exists($file)){
    $data = file_get_contents($file);
    echo $data;
}
?>

<script>
    $('#ngl_viewer_container').css('overflow-y', 'unset');
    $('.alignTblContainer').hide();
    $('#toggleLigandGroup_1').click();
    setTimeout(function() {
        $('#togglePlipInteractions_template_1').click();
    }, 3000);
</script>
