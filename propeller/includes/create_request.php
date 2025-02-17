<?php
function create_request($POST_ARRAY, $protein_id)
{
    //CREATE VIEW propeller_view AS SELECT protein.protein_id, protein.protein, uniprot, ncbi,  fold, domain, score, nbdomain, interdomain, alignment_index, domains.domains_id, protein.name, length, gene, gene_begin, gene_end, cluster, strain,
    //  species, sgroup, superkingdom, kingdom, phylum, genus, pfam, pfam_name, cazy
    //FROM tandem_protein protein JOIN tandem_species species ON (protein.species_id = species.species_id) JOIN tandem_aligned_domains domains ON (protein.protein_id = domains.protein_id)
    //WHERE fold = 'prop'
    //CREATE VIEW trefoil_view AS SELECT protein.protein_id,  protein.protein, uniprot, ncbi, fold, domain, score, nbdomain, interdomain, alignment_index, domains.domains_id, protein.name, length, gene, gene_begin, gene_end, cluster, strain,
    //  species, sgroup, superkingdom, kingdom, phylum, genus, pfam, pfam_name, cazy
    //FROM tandem_protein protein JOIN tandem_species species ON (protein.species_id = species.species_id) JOIN tandem_aligned_domains domains ON (protein.protein_id = domains.protein_id)
    //WHERE fold = 'tref'
    //
    $request = "SELECT protein.protein_id,  protein.protein, uniprot, ncbi, fold, domain, score, nbdomain, interdomain, alignment_index, domains.domains_id, protein.name, length, gene, gene_begin, gene_end, cluster, strain,
    species, sgroup, superkingdom, kingdom, phylum, genus, pfam, pfam_name, cazy
    FROM tandem_protein protein JOIN tandem_species species ON (protein.species_id = species.species_id) JOIN tandem_aligned_domains domains ON (protein.protein_id = domains.protein_id)
WHERE fold = 'prop' ";
    $rwhere = "";
    if ($protein_id != "") {
        $rwhere .= " AND protein.protein_id = '" . $protein_id . "'";
    }
    if (isset($POST_ARRAY['keyword']) && $POST_ARRAY['keyword'] != "") {
        $keywords = explode(";", $POST_ARRAY['keyword']);
        foreach ($keywords as $keyword) {
            $rwhere .= " AND name NOT LIKE '%$keyword%'";
            $rwhere .= " AND superkingdom NOT LIKE '%$keyword%'";
            $rwhere .= " AND kingdom NOT LIKE '%$keyword%'";
            $rwhere .= " AND phylum NOT LIKE '%$keyword%'";
            $rwhere .= " AND species NOT LIKE '%$keyword%'";
        }
    }
    if (isset($POST_ARRAY['protein_name']) && $POST_ARRAY['protein_name'] != "") {
        $rwhere .= " AND protein LIKE '%" . $POST_ARRAY['protein_name'] . "%'";
    }
    if (isset($POST_ARRAY['uniprot']) && $POST_ARRAY['uniprot'] != "") {
        $rwhere .= " AND uniprot LIKE '%" . $POST_ARRAY['uniprot'] . "%'";
    }
    if (isset($POST_ARRAY['refseq']) && $POST_ARRAY['refseq'] != "") {
        $rwhere .= " AND ncbi LIKE '%" . $POST_ARRAY['refseq'] . "%'";
    }
    if (isset($POST_ARRAY['protein_type']) && $POST_ARRAY['protein_type'] != "") {
        $rwhere .= " AND name LIKE '%" . $POST_ARRAY['protein_type'] . "%'";
    }
    if (isset($POST_ARRAY['species']) && $POST_ARRAY['species'] != "") {
        $rwhere .= " AND species LIKE '%" . $POST_ARRAY['species'] . "%'";
    }
    if (isset($POST_ARRAY['superkingdom']) && $POST_ARRAY['superkingdom'] != "") {
        $rwhere .= " AND superkingdom = '" . $POST_ARRAY['superkingdom'] . "'";
    }
    if (isset($POST_ARRAY['kingdom']) && $POST_ARRAY['kingdom'] != "") {
        $rwhere .= " AND kingdom = '" . $POST_ARRAY['kingdom'] . "'";
    }
    if (isset($POST_ARRAY['phylum']) && $POST_ARRAY['phylum'] != "") {
        $rwhere .= " AND phylum = '" . $POST_ARRAY['phylum'] . "'";
    }
    if (isset($POST_ARRAY['nb_domain']) && $POST_ARRAY['nb_domain'] > 0) {
        $rwhere .= " AND nbdomain = '" . $POST_ARRAY['nb_domain'] . "'";
    }
    if (isset($POST_ARRAY['domain']) && $POST_ARRAY['domain'] != "") {
        $rwhere .= " AND domains.domain = '" . $POST_ARRAY['domain'] . "'";
    }
    if (isset($POST_ARRAY['conservation_score']) && $POST_ARRAY['conservation_score'] > 0) {
        $rwhere .= " AND domains.conservation_score > '" . $POST_ARRAY['conservation_score'] . "'";
    }
    if (isset($POST_ARRAY['similarity_score']) && $POST_ARRAY['similarity_score'] > 0) {
        $rwhere .= " AND domains.score > '" . $POST_ARRAY['similarity_score'] . "'";
    }
    if (isset($POST_ARRAY['interdomain']) && $POST_ARRAY['interdomain'] > 0) {
        $rwhere .= " AND domains.interdomain <= '" . $POST_ARRAY['interdomain'] . "'";
    }
    if (isset($POST_ARRAY['domain_pfam']) && $POST_ARRAY['domain_pfam'] != "") {
        $rwhere .= " AND pfams LIKE '%" . $POST_ARRAY['domain_pfam'] . "%'";
    }
    if (isset($POST_ARRAY['pdb']) && $POST_ARRAY['pdb'] != "") {
        $rwhere .= " AND SUBSTRING(name,1,4) = '" . $POST_ARRAY['pdb'] . "'";
    }
    if (isset($POST_ARRAY['pathogen_only'])) {
        include($_SERVER['DOCUMENT_ROOT']."/predict/includes/pathogen_species_array.php");
        $rwhere .= " AND (";
        foreach ($pathogen_species as $species) {
            $rwhere .= " species LIKE '$species%' OR ";
        }
        $rwhere = rtrim($rwhere, "OR ");
        $rwhere .= " ) ";
    }
    $rgroup = " GROUP BY protein.protein_id ";
    $rhaving = "";
    $rorder = " ORDER BY score DESC";
    $request .= $rwhere . $rgroup . $rhaving . $rorder;
    return ($request);
}

?>