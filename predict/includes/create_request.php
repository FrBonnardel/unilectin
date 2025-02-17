<?php
function create_request($POST_ARRAY, $protein_id, $domains_id)
{
    //SELECT * FROM lectinpred_protein protein left JOIN lectinpred_species species ON (protein.species_id = species.species_id) left JOIN lectinpred_aligned_domains domains ON (protein.protein_id = domains.protein_id)
    //left join test_fold ON (domains.domain = test_fold.cname) where uniprot not in (select protein from lectinpred_pfam) and alt_ac not in (select protein from lectinpred_pfam)
    //SELECT fold, cname from lectin_view LEFT JOIN test_cluster ON (lectin_view.pdb = test_cluster.pdb) GROUP BY fold, cname ORDER BY fold, cname
    $request = "SELECT protein.protein_id,  fold, domains.domains_id, protein.name, length, protein_ac, uniprot, alt_ac, gene, gene_begin, gene_end, cluster, strain, 
  species, sgroup, superkingdom, kingdom, phylum, genus, domain, score, pfam_name, cazy, ref_seq, match_seq 
FROM lectinpred_protein protein JOIN lectinpred_species species ON (protein.species_id = species.species_id) 
JOIN lectinpred_aligned_domains domains ON (protein.protein_id = domains.protein_id) WHERE 1 ";
    //$request = "SELECT protein_id,  domains_id, name, length, uniprot, alt_ac, gene, gene_begin, gene_end,  species, superkingdom, kingdom, phylum, domain, score, nbdomain, alignment_index FROM lectinpred_view WHERE 1 ";
    $rwhere = "";
    if ($protein_id != "") {
        $rwhere .= " AND protein.protein_id = '" . $protein_id . "'";
    }
    if ($domains_id != "") {
        $rwhere .= " AND domains.domains_id = '" . $domains_id . "'";
    }
    if (isset($POST_ARRAY['keyword']) && $POST_ARRAY['keyword'] != "") {
        $keywords = explode(";", $POST_ARRAY['keyword']);
        foreach ($keywords as $keyword) {
            $rwhere .= " AND domain NOT LIKE '%$keyword%' ";
            $rwhere .= " AND name NOT LIKE '%$keyword%' ";
            $rwhere .= " AND protein_ac NOT LIKE '%$keyword%' ";
            $rwhere .= " AND pfam_name NOT LIKE '%$keyword%' ";
            $rwhere .= " AND species NOT LIKE '%$keyword%' ";
        }
    }
    if (isset($POST_ARRAY['remove_domain']) && $POST_ARRAY['remove_domain'] != "") {
        $keywords = explode(";", $POST_ARRAY['remove_domain']);
        foreach ($keywords as $keyword) {
            $rwhere .= " AND domain NOT LIKE '%$keyword%'";
        }
    }
    if (isset($POST_ARRAY['protein_name']) && $POST_ARRAY['protein_name'] != "") {
        $rwhere .= " AND name LIKE '%" . $POST_ARRAY['protein_name'] . "%'";
    }
    if (isset($POST_ARRAY['species']) && $POST_ARRAY['species'] != "") {
        $rwhere .= " AND (";
        $keywords = explode(";", $POST_ARRAY['species']);
        foreach ($keywords as $keyword) {
            $rwhere .= " species LIKE '%$keyword%' OR ";
        }
        $rwhere = rtrim($rwhere, "OR ");
        $rwhere .= " ) ";
    }
    if (isset($POST_ARRAY['genus']) && $POST_ARRAY['genus'] != "") {
        $rwhere .= " AND (";
        $keywords = explode(";", $POST_ARRAY['genus']);
        foreach ($keywords as $keyword) {
            $rwhere .= " genus LIKE '%$keyword%' OR ";
        }
        $rwhere = rtrim($rwhere, "OR ");
        $rwhere .= " ) ";
    }
    if (isset($POST_ARRAY['superkingdom']) && $POST_ARRAY['superkingdom'] != "") {
        $rwhere .= " AND superkingdom = '" . $POST_ARRAY['superkingdom'] . "'";
    }
    if (isset($POST_ARRAY['kingdom']) && $POST_ARRAY['kingdom'] != "") {
        $rwhere .= " AND (";
        $keywords = explode(";", $POST_ARRAY['kingdom']);
        foreach ($keywords as $keyword) {
            $rwhere .= " kingdom = '$keyword' OR ";
        }
        $rwhere = rtrim($rwhere, "OR ");
        $rwhere .= " ) ";
    }
    if (isset($POST_ARRAY['phylum']) && $POST_ARRAY['phylum'] != "") {
        $rwhere .= " AND (";
        $keywords = explode(";", $POST_ARRAY['phylum']);
        foreach ($keywords as $keyword) {
            $rwhere .= " phylum = '$keyword' OR ";
        }
        $rwhere = rtrim($rwhere, "OR ");
        $rwhere .= " ) ";
    }
    if (isset($POST_ARRAY['class']) && $POST_ARRAY['class'] != "") {
        $rwhere .= " AND (";
        $keywords = explode(";", $POST_ARRAY['class']);
        foreach ($keywords as $keyword) {
            $rwhere .= " supclass = '$keyword' OR ";
        }
        $rwhere = rtrim($rwhere, "OR ");
        $rwhere .= " ) ";
    }
    if (isset($POST_ARRAY['family']) && $POST_ARRAY['family'] != "") {
        $rwhere .= " AND (";
        $keywords = explode(";", $POST_ARRAY['family']);
        foreach ($keywords as $keyword) {
            $rwhere .= " family = '$keyword' OR ";
        }
        $rwhere = rtrim($rwhere, "OR ");
        $rwhere .= " ) ";
    }
    if (isset($POST_ARRAY['sgroup']) && $POST_ARRAY['sgroup'] != "") {
        $rwhere .= " AND (";
        $keywords = explode(";", $POST_ARRAY['sgroup']);
        foreach ($keywords as $keyword) {
            $rwhere .= " sgroup = '$keyword' OR ";
        }
        $rwhere = rtrim($rwhere, "OR ");
        $rwhere .= " ) ";
    }
    if (isset($POST_ARRAY['gene']) && $POST_ARRAY['gene'] != "") {
        $rwhere .= " AND gene = '" . $POST_ARRAY['gene'] . "'";
    }
    if (isset($_POST['fold']) && $_POST['fold'] != "") {
        $rwhere .= " AND fold = '" . $POST_ARRAY['fold'] . "'";
    }
    if (isset($POST_ARRAY['domain']) && $POST_ARRAY['domain'] != "") {
        $rwhere .= " AND domain = '" . $POST_ARRAY['domain'] . "'";
    }
    if (isset($POST_ARRAY['similarity_score']) && $POST_ARRAY['similarity_score'] > 0) {
        $rwhere .= " AND score > '" . $POST_ARRAY['similarity_score'] . "'";
    }
    if (isset($POST_ARRAY['domain_pfam']) && $POST_ARRAY['domain_pfam'] != "") {
        $rwhere .= " AND pfam LIKE '%" . $POST_ARRAY['domain_pfam'] . "%'";
    }
    if (isset($POST_ARRAY['domain_pfam_name']) && $POST_ARRAY['domain_pfam_name'] != "") {
        $rwhere .= " AND pfam_name LIKE '%" . $POST_ARRAY['domain_pfam_name'] . "%'";
    }
    if (isset($POST_ARRAY['uniprot']) && $POST_ARRAY['uniprot'] != "") {
        $rwhere .= " AND uniprot LIKE '%" . $POST_ARRAY['uniprot'] . "%'";
        $rwhere .= " AND alt_ac LIKE '%" . $POST_ARRAY['uniprot'] . "%'";
    }
    //if (isset($POST_ARRAY['pdb']) && $POST_ARRAY['pdb'] != "") {
    //    $rwhere .= " AND SUBSTRING(uniprot,1,4) = '" . $POST_ARRAY['pdb'] . "'";
    //}
    if (isset($POST_ARRAY['pdb_only'])) {
        $rwhere .= " AND char_length(alt_ac) = 6 AND SUBSTRING(alt_ac,5,1) = '_' ";
    }
    if (isset($POST_ARRAY['multiple_class'])) {
        $rwhere .= " AND protein_ac in (SELECT distinct(protein) FROM lectinpred_aligned_domains GROUP BY protein HAVING count(distinct(domain)) > 1)";
    }
    if (isset($POST_ARRAY['pathogen_only'])) {
        include("pathogen_species_array.php");
        $rwhere .= " AND (";
        foreach ($pathogen_species as $species) {
            $rwhere .= " species LIKE '$species%' OR ";
        }
        $rwhere = rtrim($rwhere, "OR ");
        $rwhere .= " ) ";
    }
    if (isset($POST_ARRAY['uniref_cluster'])) {
        $rwhere .= " AND cluster != '' GROUP BY cluster ";
    }
    $request .= $rwhere ;
    //echo '<pre>'.$request.'</pre>';
    return ($request);
}

?>