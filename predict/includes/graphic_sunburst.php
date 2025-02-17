<style>
    .tooltip {
        position: absolute;
        text-align: center;
        width: 150px;
        height: auto;
        padding: 2px;
        font: 12px sans-serif;
        background: black;
        border: 0px;
        border-radius: 8px;
        color: white;
        box-shadow: -3px 3px 15px #888888;
        opacity: 0;

    }

    #sidebar {
        float: right;
        width: 100px;
    }

    #sequence {
        width: 600px;
        height: 70px;
    }

    #legend {
        padding: 5px;
        text-align: center;
        float: right;
    }

    #sequence text, #legend text {
        font-weight: 600;
        fill: #fff;
    }

    #chart {
        position: relative;
    }

    #chart path {
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
</style>

<div class="div-border-title">
    <?php echo $POST_ARRAY['domain'] ?> Taxonomic distribution
</div>
<div class="div-border" style="width:100%; height:100%;display:inline-block;padding-left:30px;">
    <div id="chart" style="display:inline-block;margin-top:20px;">
        <div id="tooltip" class="tooltip"></div>
    </div>
    <div id="legend" style="display:inline-block;"></div>
</div>
<script type="text/javascript" src="/predict/js/lectinpred_sunburst.js"></script>

<script>
    function unsync_create_sunburst() {
        return new Promise(resolve => {
            setTimeout(() => {
                var data = [<?php echo $graphic_sunburst_data ?>];
                var colors = {
                    "Bacteria": "#5687d1",
                    "Eukaryota": "#7b615c",
                    "Archaea": "#de783b",
                    "Viruses": "#6ab975",
                    "Unclassified": "#000000"
                };
                var levels = {
                    0: "superkingdom",
                    1: "kingdom",
                    2: "phylum",
                    3: "species"
                };
                var total_objects_sunburst = <?php echo $total_objects_sunburst; ?>;
                create_sunburst(300, 300, "#chart", "#tooltip", "#legend", data, colors, levels, total_objects_sunburst);
            }, 10);
        });
    };
    unsync_create_sunburst();
</script>