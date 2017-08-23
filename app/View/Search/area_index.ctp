<?php 
//header
echo $this->element('header'); 
?>

<?php
foreach($area as $a){
    echo $a['Area']['name'];
    echo '<br/>';
    foreach ($a['Prefecture'] as $p){
        echo $this->Html->link($p['name'], array('action' => 'area', $p['short_name']) );  
        echo '<br/>';
    }
    echo '<br/>';
} 

/*
foreach($prefecture as $p){
    echo $this->Html->link($p['Prefecture']['name'], array('action' => 'area', $p['Prefecture']['short_name']) );  
    echo '<br/>';
} 
*/

?>