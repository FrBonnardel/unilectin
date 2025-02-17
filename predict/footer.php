<div id="content-bottom-margin" style='padding-top:30px;padding-bottom:60px;display:inline-block;width:100%;'>
<div style="float:left;width:30%;">
Copyright &#9400; UniLectin 2017-2020
<br><a href="http://www.cermav.cnrs.fr/">CERMAV</a> | <a href="http://www.cnrs.fr">CNRS</a> | <a href="https://web.expasy.org/groups/pig/">PIG</a> | <a href="https://www.sib.swiss/">SIB</a> | <a href="/pages/license">Terms of use</a>
</div>
    <div style="float:right;width:70%;text-align:right;">
        How to cite: François Bonnardel, Julien Mariethoz, Serge Pérez, Anne Imberty, Frédérique Lisacek, LectomeXplore, an update of UniLectin for the discovery of carbohydrate-binding proteins based on a new lectin classification, Nucleic Acids Research
        <a href="https://doi.org/10.1093/nar/gkaa1019">doi.org/10.1093/nar/gkaa1019</a>
    </div>
</div>
</div>
  </div>
  <script>
    //AUTO RESIZE CONTENT SCROLLABLE DIV
    $(document).ready(function() {
      var resizeDelay = 200;
      var doResize = true;
      var resizer = function() {
        if (doResize) {
          var heightSlider = $('#header').height();
          $('#content_scroll').css({
            height: $(window).height() - heightSlider
          });
          doResize = false;
        }
      };
      var resizerInterval = setInterval(resizer, resizeDelay);
      resizer();
      $(window).resize(function() {
        doResize = true;
      });
    });
  </script>
  </body>
</html>