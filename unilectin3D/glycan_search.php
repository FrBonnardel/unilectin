<?php include($_SERVER['DOCUMENT_ROOT'] . "/header.php"); ?>
<title>Glycan search</title>
<center>
    <label class="title_main">UniLectin3D glycan search</label>
</center>
<script>
    function toggle_glycans(button){
        var glycan = button.val();
        console.log(glycan);
        $('.iupac').hide();
        var glycans = $('#glycan_filter').val();
        var glycan_list = [];
        if (glycans != ''){var glycan_list = glycans.split(',');}
        if (button.hasClass('btn-success')) {
            for (var i = 0; i < glycan_list.length; i++) {
                if (glycan_list[i] == glycan) {
                    glycan_list.splice(i, 1);
                }
            }
        }
        else{
            glycan_list.push(glycan) ;
        }
        $('#glycan_filter').val(glycan_list);
        button.toggleClass('btn-success');
        console.log(glycan_list);
        if(glycan_list.length == 0){return (1);}
        $('.iupac').each(function(){
            //console.log($(this).html());
            //console.log(iupac_button.html());
            var iupac_button = $(this);
            var display = 1;
            glycan_list.forEach(function(value) {
                if (! iupac_button.hasClass(value)) {
                    display = 0;
                }
            });
            if(display == 1) {
                iupac_button.show();
            }
        });
    }
</script>

<input id='glycan_filter' type="hidden" value=''>
<div  id='sugar_list'>
    <?php
    $monosactab = [
        'D-Manp'      => 'Man'     ,
        'D-GlcpNAc'   => 'GlcNAc'  ,
        'D-Glcp'      => 'Glc'     ,
        'D-Fruf'      => 'Fru'    ,
        'D-Galp'      => 'Gal'     ,
        'D-GalpNAc'   => 'GalNAc'  ,
        'L-Fucp'      => 'Fuc'     ,
        'D-Neup5Ac'   => 'NeuAc'  ,
        'D-ManpNAc'   => 'ManNAc'  ,
        'D-Fucp'      => 'Fuc'     ,
        'D-Talp'      => 'Tal'     ,
        'D-GalNp'     => 'GalN'    ,
        'D-Galf'      => 'Galf'    ,
        'L-Rhap'      => 'Rha'     ,
        'D-GlcpN'     => 'GlcN'    ,
        'D-Frup'      => 'Fru'     ,
        'D-Arap'      => 'Ara'     ,
        'L-Galp'      => 'Gal'     ,
        'D-Xylp'      => 'Xyl'     ,
        'D-Neup5Gc'   => 'NeuGc'   ,
        'D-Murp'      => 'Mur'    ,
        'D-ManpN'     => 'ManN'    ,
        'D-Tyvp'      => 'Tyv'     ,
        'D-Abep'      => 'Abe'     ,
        'LD-manHepp'  => 'LDmanHep',
        'DD-manHepp'  => 'DDmanHep'
    ];
    ksort($monosactab);
    foreach($monosactab as $glycan => $iupac){
        echo "<button class='btn btn-primary' style='width:160px;height:113px;' onclick='toggle_glycans($(this));' value='$glycan'>$glycan<br><img src='../templates/png/$iupac.png'></button>";
    }
    ?>
</div>

<?php
$request = "SELECT iupac, monosac from lectin_view WHERE iupac != '' GROUP BY iupac ORDER BY LENGTH(iupac) ";
$results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
echo "<div name='iupac_list' id='iupac_list'>";
$id_iupac=0;
while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    $row['monosac'] = str_replace(",","",$row['monosac']);
    echo "<a style='display:none;margin:5px;' class='btn btn-secondary iupac {$row['monosac']}' href='/unilectin3D/search?iupac={$row['iupac']}'>{$row['iupac']}<br><img src='../../templates/png/".$row['iupac'].".png'></a>";
    $id_iupac+=1;
}
echo "</div>";
?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/footer.php"); ?>

