<?php
/**
 * Created by PhpStorm.
 * User: François Bonnardel
 * Date: 14/05/2020
 * Time: 14:04
 */

?>
<script src="/js/d3.v3.min.js"></script>
<script type="text/javascript" src="/js/sunburst_sequences_small.js?v=65468486"></script>

<style>
    .tooltip{
        position: absolute;
        text-align: center;
        width: 150px;
        height: auto;
        padding: 2px;
        font: 12px sans-serif;
        background: black;
        border: 0px;
        border-radius: 8px;
        color:white;
        box-shadow: -3px 3px 15px #888888;
        opacity:0;

    }
    .legend {
        padding: 5px;
        text-align: center;
        font-weight: 600;
        fill: #fff;
    }

    .chart {
        position: relative;
        stroke: #fff;
    }

    #explanation, #tutorial {
        top: 5px;
        left: 5px;
        width: 140px;
        text-align: center;
        color: #666;
        z-index: -1;
    }

    #percentage {
        font-size: 1em;
    }
    h2{
        padding:5px;font-size: 18px; text-align: justify; text-justify: inter-word;
    }
</style>
    <div style="display:inline-block;width:100%;float:left;padding:5px;margin-left:20%;margin-right:20%;">
        <h2>
            Browse by Fold > Class > Familly
        </h2>
        (The number of families is indicated when the mouse is over)
        <div style="position: relative; width:100%; height:260px;display:inline-block;padding:10px;padding-top:0px;">
            <?php
            include("./sunburst_fold.php");
            ?>
        </div>
    </div>
    <div style="display:inline-block;width:50%;float:left;padding:5px;">
        <h2>
            Browse by Origin > Species
        </h2>
        (The number of species is indicated when the mouse is over)
        <div style="position: relative; width:100%; height:260px;display:inline-block;padding:10px;padding-top:0px;">
            <?php
            include("./family_sunburst_small.php");
            ?>
        </div>
    </div>
    <div style="display:inline-block;width:50%;float:left;padding:5px;border-left:1px solid grey;">
        <h2>
            Browse by monosaccharide and associate IUPAC sequence
        </h2>
        (The number of distinct iupac is indicated when the mouse is over)
        <div style="position: relative; width:100%; height:260px;display:inline-block;padding:10px;padding-top:0px;">
            <?php
            include("./sunburst_carbo.php");
            ?>
        </div>
    </div>

    <div style='width:100%;padding-left:3%;padding-right:6%;display: inline;'><div style='width:94%;border-top:1px solid black;display: inline-block;'></div></div>
    <h2>
        Browse by Fold > Class > Familly
    </h2>
(Click on panel to expand it and on the blue search button to explore)

    <?php
    //DISPLAY THE FAMILY TREE
    include("./tree.php");
    ?>
    <!--   cat list  -->
    <div style='width:100%;padding-left:3%;padding-right:6%;display: inline;'><div style='width:94%;border-top:1px solid black;display: inline-block;'></div></div>

  </div>
