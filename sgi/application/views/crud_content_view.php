<?php 

if($this->session->flashdata('message')){?>
  <div class="alert alert-info">      
    <?php echo $this->session->flashdata('message')?>
  </div>
<?php } 
if($this->session->flashdata('error')){?>
  <div class="alert alert-info">      
    <?php echo $this->session->flashdata('error')?>
  </div>
<?php } ?>
<div style='height:20px;'></div>  
<div>
    <?php echo $output;    ?>
</div>

<?php
if(isset($dropdown_setup)) {
	$this->load->view('dependent_dropdown', $dropdown_setup);
}
?>

