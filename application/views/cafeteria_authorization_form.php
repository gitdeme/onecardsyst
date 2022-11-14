
<div class="col-lg-12 bg-white">
    <div class="col-lg-3"> 

        <audio controls id="alarm" style="display: none">
            <source src="<?php echo base_url() ?>resources/sound/RedAlert.mp3" type="audio/mpeg">

        </audio>

    </div>



    <div class="col-lg-9 col-lg-offset-3" id="barcodeentry"> 
        <label style="font-family:vijaya; font-size:40px;  color:#008000;">   Cafeteria <?php if (isset($cafnum)) {
    echo $cafnum;
} ?>  </label>
        <input type="text" name="barcode" style="height: 50px" id="bcode"  class="form-control"/>
    </div>
    <div class="col-lg-3">  </div>   
    <div class="col-lg-9 col-lg-offset-3"  id="status">   </div>
</div>




