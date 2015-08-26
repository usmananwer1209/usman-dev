<!-- 
<div class="row disp_none" id="card_descr">
</div>
-->
<style>.card_des {
		z-index: -1 !important;} </style>
<div id="card_descr" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Card description</h4>
            </div>
            <div class="modal-body grid simple">
                <div class="grid-body no-border">
                    <div class="row">
                        <div class="col-md-11 col-sm-10 col-xs-10 col-centered">
                            <div class="form-group">
                                <label class="form-label">Source(s) of data</label>
                                <span class="help"></span>
                                <div class="controls">
                                    <input type="text" id="sources" class="form-control" placeholder="(Will be provided after refreshing data)" disabled="disabled"  value="" > 
                                </div>
                            </div>

                            <!--
                            <div class="form-group">
                                <label class="form-label">Actual data points used to populate the card</label>
                                <span class="help"></span>
                                <div class="controls">
                                    <input type="text" id="data_points" class="form-control" disabled="disabled"  value="You can put anything here" >
                                </div>
                            </div>
                            -->

                            <div class="form-group">
                                <label class="form-label" >Author/Creator</label>
                                <div class="controls">
                                    <input type="text" id="author_name" class="form-control" disabled="disabled" value="<?php echo "$user->first_name $user->last_name"; ?>" >
                                    <input type="hidden" id="author" class="form-control" value="<?php echo "$user->first_name $user->id"; ?>" >
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" >Title</label>
                                <div class="controls">
                                    <input type="text" id="name" class="form-control" placeholder="Card's Title" value="<?php if (!empty($op) && $op == "edit_") { echo $obj->name; } ?>" >
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <div class="controls">
                                    <textarea id="description" name="description" type="text" class="form-control" 
                                              placeholder="Description" rows="5"><?php echo $description; ?></textarea>
                                </div>
                            </div>

                            <br/>
                            <button class="btn btn-success" id="save_desc" type="button">Save changes</button>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

