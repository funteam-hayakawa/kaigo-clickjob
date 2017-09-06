<?php 
//header
echo $this->element('header'); 
?>

<?php
    echo $this->Form->create(false, array('type' => 'post',
                                                   'url' => array('controller' => 'search', 'action' => 'result')
    )); 
    echo '<table>';
    foreach ($commitmentTextConf as $key => $ct){
        echo '<tr>';
            echo '<td>';
                echo $ct['name'];
            echo '</td>';
            echo '<td>';
                foreach ($ct['list'] as $c){
                    echo $this->Form->input('Search.'.$key.'.', array(
                                            'type'=>'checkbox',
                                            'required' => false,
                                            'hiddenField' => false,
                                            'value' => $c['code'],
                                            
                    ));
                    echo $this->Html->link($c['text'], array('action' => 'feature' ,$c['url'])); 
                }
            echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
?>
<?php echo $this->Form->end(array('label' => __('Search'),
    'class' => 'btn btn-default btn-search-margin',
)) ?>