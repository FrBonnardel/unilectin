<style>
    /*//------- {{ Variables }} -------//*/
    /*//------- {{ Styles }} -------//*/
    *, *:before, *:after {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    body {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    #wrapper {
        position: relative;
    }
    .branch-blank {
        position: relative;
        margin-left: 400px;
    }
    .branch {
        position: relative;
        margin-left: 320px;
    }
    .branch:before {
        content: "";
        width: 20px;
        border-top: 2px solid lightgrey;
        position: absolute;
        left: -40px;
        top: 50%;
        margin-top: 1px;
    }
    .blank {
        position: relative;
        min-height: 60px;
        border-left: 2px solid white;
        left: -50px;
    }
    .entry {
        position: relative;
        /* position: absolute; */
        min-height: 24px;
    }
    .entry:before {
        content: "";
        height: 100%;
        border-left: 2px solid lightgrey;
        position: absolute;
        left: -20px;
    }
    .entry:after {
        content: "";
        width: 20px;
        border-top: 2px solid lightgrey;
        position: absolute;
        left: -20px;
        top: 50%;
        margin-top: 1px;
        /*border: 2px solid black;*/
    }
    .entry:first-child:before {
        width: 10px;
        height: 50%;
        top: 50%;
        margin-top: 2px;
        border-radius: 10px 0 0 0;
    }
    .entry:first-child:after {
        height: 10px;
        border-radius: 10px 0 0 0;
        /* border: 5px solid red; */
    }
    .entry:first-child {
    }

    .entry:last-child:before {
        width: 10px;
        height: 50%;
        border-radius: 0 0 0 10px;
        /* border: 5px solid red; */
    }
    .entry:last-child:after {
        height: 10px;
        border-top: none;
        border-bottom: 2px solid lightgrey;
        border-radius: 0 0 0 10px;
        margin-top: -9px;
    }
    .entry.sole:before {
        display: none;
    }
    .entry.sole:after {
        width: 20px;
        height: 0;
        margin-top: 1px;
        border-radius: 0;
    }

    .tree_label {
        display: block;
        width: 280px;
        padding: 0;
        /*line-height: 20px;*/
        text-align: center;
        border: 2px solid black;
        border-radius: 5px;
        position: absolute;
        left: 0;
        top: 50%;
        margin-top: -8px;
        color:black;
        font-size:12px;
    }
    .ip:before {
        content: "";
        width: 10px;
        border-right: 2px solid lightgrey;
        position: absolute;
        top: 30px;
        margin-top: 1px;
        height: 50px;
    }
    .ip {
        margin-top: -110px;
        position: absolute;
        z-index: -1;
    }

    /*We will apply the hover effect the the lineage of the element also*/
    /*, .tree li a:hover+ul li a*/
    .entry span:hover, .entry span:hover+div span, .tree_label:hover {
        background: #c8e4f8; color: #000; border: 1px solid #94a0b4;
    }
    /*Connector styles on hover FIX THIS*/
    /*.tree li a:hover+ul li::after, */
    .entry span:hover+div span::after,
        /*.tree li a:hover+ul li::before, */
    .entry span:hover+div span::before,
        /*.tree li a:hover+ul::before, */
    .entry span:hover+div::before,
        /*.tree li a:hover+ul ul::before*/
    .entry span:hover+span::before {
        border-color:  #000;
    }

    /*FROM BRYAN*/
    .hasMore {
        border: 2px #337ab7 solid !important;
    }

    .title {
        padding-left: 50px;
        white-space: nowrap;
    }

    #L100000, #L200000, #L300000 {
        top: -20px;
        position: absolute;
    }

    #root {
        margin-top: -6px;
    }
</style>

<?php
$root = array();
$fold_id=1;
$new_class_id=1;
$famille_id=1;
$fold_tmp = "";
echo "<div id=\"wrapper\">";
include ("connect.php");
$connexionBIG = connectdatabaseBIG();
$request = "SELECT fold, domain as lecclass FROM lectinpred_view GROUP BY fold, domain ORDER BY fold, domain" ;
$results = mysqli_query($connexionBIG, $request) or die ("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    $new_class_sole = "sole";
    $parts = [$row['fold'], $row['lecclass']];
    if($fold_tmp != ""){
        if($row['fold'] != $fold_tmp){
            echo "</div></div>";
        }else{
            echo "<script>$( \".entry_class_$fold_id\" ).removeClass( \"sole\" )</script>";
            $new_class_sole = "";
        }
    }
    if($row['fold'] != $fold_tmp){
        $fold_tmp = $row['fold'];
        $fold_id++;
        echo "<div class=\"entry\" id=\"entry\"><span class=\"tree_label toggleable hasMore\">".substr($fold_tmp,0,45);
        echo "<a class=\"btn-lg btn-primary btn-sm\" href ='./search?fold=$fold_tmp' style=\"width:20px;height:18px;margin:0;padding:2px;font-size:10px;float:right;\"><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span></a>";
        echo "</span><div class=\"branch lv2\" style=\"display: none;\">";
    }
    $new_class_tmp = $row['lecclass'];
    echo "<div class=\"entry $new_class_sole entry_new_class_$fold_id\" id='entry_new_class_$fold_id'><span class=\"tree_label\" style='width:250px;'>$new_class_tmp";
    echo "<a class=\"btn-lg btn-primary btn-sm\" href ='./search?fold=$fold_tmp&domain=$new_class_tmp' style=\"width:20px;height:18px;margin:0;padding:2px;font-size:10px;float:right;\"><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span></a>";
    echo "</span></div>";
}
echo "</div></div>";
echo "</div></div>";
echo "</div>";
?>
<script>
    function init() {
        $(".toggleable").on("click", function() {
            if (this.nextElementSibling) {
                var next_branch = $(this).nextAll(".branch");
                next_branch.toggle();
                $(this).toggleClass("hasMore");
                if ($(this).attr('id') == 'root') {
                    if ($(this).css("margin-top") == "25px") {
                        $(this).css("margin-top", "-7px");
                    } else {
                        $(this).css("margin-top", "25px");
                    };
                };
            }
        });

        $(".top-button").on("click", function() {
            if (this.nextElementSibling) {
                if ($(this.nextElementSibling).css('display') == "none") {
                    $("<div class='blank'><span></span></div>").insertBefore($(this).parent());
                    $('.top-button').parent().removeClass('special');
                } else {
                    $(this).parent().siblings('.blank').slideUp(600);
                    $('.top-button').parent().addClass('special');
                }
                $(this.nextElementSibling).slideToggle(600);
            };
        });

        $(".bottom-button").on("click", function() {
            if ($(this.nextElementSibling).css('display') == "none") {
                $("<div class='blank'><span></span></div>").insertAfter($(this).parent());
            } else {
                // remove inserted stuff
                $('.blank').slideUp(500);
            }
            $(this.nextElementSibling).slideToggle(600);
        });
    }
    init();
</script>
