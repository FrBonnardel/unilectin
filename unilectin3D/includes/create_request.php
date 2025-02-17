<?php
function create_request($POST_ARRAY)
{
    $rwhere = '';
    if (isset($_POST['species']) && $_POST['species'] != "") {
        $rwhere .= " AND species LIKE '%" . $_POST['species'] . "%'";
    }
    if (isset($_POST['class']) && $_POST['class'] != "") {
        $rwhere .= " AND class = '" . $_POST['class'] . "'";
    }
    if (isset($_POST['monosac']) && $_POST['monosac'] != "") {
        $rwhere .= " AND ( monosac like '%" . $_POST['monosac'] . "' OR monosac like '%" . $_POST['monosac'] . ",%' )";
    }
    if (!isset($_POST['iupac_exact']) && isset($_POST['iupac']) && $_POST['iupac'] != "") {
        $rwhere .= " AND iupac LIKE '%" . $_POST['iupac'] . "%' ";
    }
    if (isset($_POST['iupac_exact']) && isset($_POST['iupac']) && $_POST['iupac'] != "") {
        $rwhere .= " AND iupac = '" . $_POST['iupac'] . "' ";
    }
    if (isset($_POST['origin']) && $_POST['origin'] != "") {
        $rwhere .= " AND origin = '" . $_POST['origin'] . "'";
    }
    if (isset($_POST['fold']) && $_POST['fold'] != "") {
        $rwhere .= " AND fold = '" . $_POST['fold'] . "'";
    }
    if (isset($_POST['comments']) && $_POST['comments'] != "") {
        $rwhere .= " AND comments LIKE '%" . $_POST['comments'] . "%'";
    }
    if (isset($_POST['title']) && $_POST['title'] != "") {
        $rwhere .= " AND title LIKE '%" . $_POST['title'] . "%'";
    }
    if (isset($_POST['family']) && $_POST['family'] != "") {
        $rwhere .= " AND family = '" . $_POST['family'] . "'";
    }
    if (isset($_POST['uniprot']) && $_POST['uniprot'] != "") {
        $rwhere .= " AND uniprot = '" . $_POST['uniprot'] . "'";
    }
    if (isset($_POST['pdb']) && $_POST['pdb'] != "") {
        $rwhere .= " AND pdb = '" . $_POST['pdb'] . "'";
    }
    if (isset($_POST['resolution']) && $_POST['resolution'] != "0") {
        $rwhere .= " AND resolution <= '" . $_POST['resolution'] . "'";
    }
    return ($rwhere);
}

?>