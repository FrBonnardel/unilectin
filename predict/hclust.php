<?php
/**
 * Created by PhpStorm.
 * User: François Bonnardel
 * Date: 31/10/2019
 * Time: 16:00
 */

function count_common_value($values1, $values2){
    $total_common=0;
    for ($i = 0; $i < count($values1); $i++) {
        if($values1[$i] == $values2[$i]){
            $total_common++;
        }
    }
    return $total_common;
}

function find_closer($tax_values, $refvalues){
    //echo '<pre>'; print_r($tax_values); echo '</pre>';
    $phylo_total_common=[];
    foreach ($tax_values as $phylo => $values){
        $phylo_total_common[$phylo] = count_common_value($refvalues, $values);
    }
    $closer_phylo = array_keys($phylo_total_common, max($phylo_total_common))[0];
    return $closer_phylo;
}

function hclust($tax_values){
    $order = array();
    reset($tax_values);
    $phylo = key($tax_values);
    $refvalues = $tax_values[$phylo];
    unset($tax_values[$phylo]);
    $order[$phylo]=1;
    $clust_index=2;
    while(count($tax_values)>1){
        $closer_phylo = find_closer($tax_values, $refvalues);
        $refvalues = $tax_values[$closer_phylo];
        unset($tax_values[$closer_phylo]);
        $order[$closer_phylo]=$clust_index;
        $clust_index++;
    }
    reset($tax_values);
    $closer_phylo = key($tax_values);
    $order[$closer_phylo]=$clust_index;
    $clust_index++;
    return $order;
}

$order = hclust($tax_values);