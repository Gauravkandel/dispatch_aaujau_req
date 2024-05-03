<div id="add-task-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">Add task</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="add_task" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-3 py-0" id="addCardBox">
                    
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-blue waves-effect waves-light submitEditForm">Submit</button>
                </div>
                
            </form>

            <form id="add_task" action="{{ route('tasks.store') }}" method="POST">
                <div class="modal-body px-3 py-0">
                    
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-blue waves-effect waves-light submittaskForm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="edit-task-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">Edit task</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <form id="edit_task" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body px-3 py-0" id="editCardBox">
                    
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-blue waves-effect waves-light submitEditForm">Submit</button>
                </div>
                
            </form>
        </div>
    </div>
</div>

<div id="show-map-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header border-0">
                <h4 class="modal-title">Select Location</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body px-3 py-0">
                
                <div class="row">
                    <form id="task_form" action="#" method="POST" style="width: 100%">
                        <div class="col-md-12">
                            <div id="googleMap" style="height: 500px; min-width: 500px; width:100%"></div>
                            <input type="hidden" name="lat_input" id="lat_map" value="0" />
                            <input type="hidden" name="lng_input" id="lng_map" value="0" />
                            <input type="hidden" name="for" id="map_for" value="" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-blue waves-effect waves-light selectMapLocation">Ok</button>
                <!--<button type="Cancel" class="btn btn-blue waves-effect waves-light cancelMapLocation">cancel</button>-->
            </div>
        </div>
    </div>
</div>