<div id="content-bottom-margin" style='padding-top:30px;padding-bottom:60px;display:inline-block;width:100%;'>
<div style="float:left;width:30%;">
Copyright &#9400; UniLectin 2017-2020
<br><a href="http://www.cermav.cnrs.fr/">CERMAV</a> | <a href="http://www.cnrs.fr">CNRS</a> | <a href="https://web.expasy.org/groups/pig/">PIG</a> | <a href="https://www.sib.swiss/">SIB</a> | <a href="/pages/license">Terms of use</a>
</div>
<div style="float:right;width:70%;text-align:right;">
How to cite: F Bonnardel, J Mariethoz, S Salentin, X Robin, M Schroeder, S Perez, F Lisacek, A Imberty; UniLectin3D, a database of carbohydrate binding proteins with curated information on 3D structures and interacting ligands, Nucleic Acids Research, Nucleic Acids Research, Volume 47, Issue D1, 8 January 2019, Pages D1236–D1244, <a href="https://academic.oup.com/nar/advance-article/doi/10.1093/nar/gky832/5098605">doi:10.1093/nar/gky832</a>
</div>
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