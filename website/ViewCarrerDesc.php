<?php 
require_once ('../admin/BL/HomeManager.php');
$JobObject=new HomeManager();
$JobCareerDetail=$JobObject->GetAllJobById($_REQUEST['val']);	
?>
                          <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content ">
                              <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Position :<?php echo $JobCareerDetail[0]->JOB_POSITION; ?></h4>
                              </div>
                              <div class="modal-body" style="overflow:scroll; height:600px">

                                <?php echo $JobCareerDetail[0]->JOB_DISCRIPTION; ?>
                              </div>
                            </div>
                          </div>
                        