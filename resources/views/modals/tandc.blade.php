<div id="create-tandc-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <form method="post" action="{{ route('cms.save',1) }}" id="tandc_form">
        @csrf
     <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">Terms and Conditions</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body px-3 py-0">
                
               
               
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="field-3" class="control-label">Content</label>
                            <textarea name="content" class="form-control" id="example-textarea" rows="25">{{ $cms[0]->content }}</textarea>
                        </div>
                    </div>
                </div>

               
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
            </div>
        </div>
     </div>
   </form>
</div><!-- /.modal -->