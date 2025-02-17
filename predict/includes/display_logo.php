<script src="/js/jquery-2.2.2.min.js"></script>
<script src="/js/bootstrap.min.js"></script>

<script src="/predict/skylign/less.js" type="text/javascript"></script>
<script src="/predict/skylign/modernizr-2.0.6.js"></script>

<link rel="stylesheet" media="screen" type="text/css" href="/css/bootstrap.min.css?v=156168496152">
<link rel="stylesheet/less" type="text/css" href="/predict/skylign/app.less">

<style>
    .logo_xaxis{
        display:none;
    }
    .logo_yaxis{
        height:300px;
    }
    .logo_wrapper {
        background: #eee;
        border-top: 2px solid #ccc;
        border-bottom: 2px solid #ccc;
        padding: 0 1em;
        margin: 1em 0;
    }
    .clearfix {
        *zoom: 1;
    }
    .logo_container {
        position: relative;
        height: 310px;
        overflow: hidden;
        overflow-x: auto;
        margin-left: 2px;
        border: 1px solid #999;
        min-width:920px;
    }
    .logo_controls {
        position: absolute;
        left: 3.1em;
        top: 0;
    }
    .logo_settings {
        display: none;
        z-index: 6;
        position: relative;
        background: #fff;
        margin: 1em;
        padding: 0;
        border: 2px solid #ccc;
    }
</style>

<div class="logo_wrapper clearfix" style="display:block;overflow-x:auto;">
<div id="logo" class="logo" style="display:flex;" data-logo='<?php echo file_get_contents('../skylign/'.$_GET['domain'].'.json'); ?>'></div>
<div id="col_info"></div>
</div>

<script src="/predict/skylign/scroller.js"></script>
<script src="/predict/skylign/hmm_logo.bundle.js"></script>
<script src="/predict/skylign/carousel.js"></script>
<script src="/predict/skylign/jquery.qtip.js"></script>
<script src="/predict/skylign/application.js"></script>