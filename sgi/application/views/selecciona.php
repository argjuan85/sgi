<?
$options = array(
        'small'         => 'Small Shirt',
        'med'           => 'Medium Shirt',
        'large'         => 'Large Shirt',
        'xlarge'        => 'Extra Large Shirt',
);

$shirts_on_sale = array('small', 'large');

echo form_open('pedidos/reporte'); 
echo form_dropdown('shirts', $options, 'large');

?>
<input id="elegir" type="submit" name="ELEGIR" value="ELEGIR" onClick="parent.jQuery.fancybox.close();" >
<?php echo form_close()?>