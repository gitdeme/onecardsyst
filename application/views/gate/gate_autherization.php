<div class="card shadow mb-4">
    <div class="col-sm-12 card-body " style="min-height: 400px">  
        <div class="col-md-7 mt-5 row">                               

            <!--            <div  class="col-md-12">
                            <select name="rtype" id="rtype" class="form-control">
                                <option value="out">Exit( መዉጫ በር)   </option>  
                                <option value="in"> Entry(መግቢያ በር) </option> 
                            </select>   
                        </div>-->
            <div class="col-md-2"> 
                <audio controls id="alarm" style="display: none">
                    <source src="<?php echo base_url() ?>resources/sound/RedAlert.mp3" type="audio/mpeg">

                </audio> 
            </div>
            <div class="col-md-10 mt-3">
                <input type="text" id="bcID_campus_gate"  name="barcodeID" class="form-control" style="border:solid 1px #003366; height: 60px"/>
            </div>  


        </div> 
        <div  id="displayinfo" class="col-sm-10 col-lg-offset-2 text-primary font-weight-bold">

        </div> 
    </div>

</div>

