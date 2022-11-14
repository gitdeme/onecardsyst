
    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
          <div class="col-lg-5 d-none d-lg-block"></div>
          <div class="col-lg-7">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Edit Faculty</h1>
              </div>
              <?php echo form_open("BasicData/edit_faculty/".$faculty["faculity_code"]) ?>
               
                <div class="form-group">
                    <input type="text" name="faculty" value="<?php echo ($this->input->post('faculty') ? $this->input->post('faculty') : $faculty['faculity_name']); ?>" class="form-control form-control-user" id="faculty" />
                </div>
               
              
                  <input class="btn btn-primary btn-user btn-block" type="submit" name="send" value=" Save "/>
              
              </form>
            
            </div>
          </div>
        </div>
      </div>
    </div>


