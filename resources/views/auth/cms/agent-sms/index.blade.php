@extends('layouts.vertical', ['demo' => 'creative', 'title' => getAgentNomenclature().' SMS'])
@section('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet">
<style>
    textarea.form-control {
    height: auto !important;
}
</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ __("SMS") }}</h4>
            </div>
        </div>
    </div>
    <div class="row cms-cols">
        <div class="col-lg-5 col-xl-3 mb-2">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4>{{ __("List") }}</h4>
                    </div>
                   <div class="table-responsive pages-list-data">
                        <table class="table table-striped w-100">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">{{ __("Template Name") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($templates as $template)
                                    <tr class="page-title active-page template-page-detail" data-template_id="{{$template->id}}" data-show_url="{{route('cms.agent-sms.template.show', ['id'=> $template->id])}}">
                                        <td>
                                            <a class="text-body d-block" href="javascript:void(0)">{{$template->label}}</a>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                   </div>
                </div>
            </div>
        </div>
        <div class="col-md-7 col-xl-9 mb-2 al_cms_template">
            <div class="card">
                <div class="card-body p-3" id="edit_page_content">
                    <div class="row mb-2">
                        <div class="col-12 text-right">
                            <button type="button" class="btn btn-info" id="update_template"> {{ __("Publish") }}</button>
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" id="template_id" value="">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-md-10 mb-2">
                                    <label for="title" class="control-label">{{ __("Content") }}</label>
                                    <textarea class="form-control" id="content" placeholder="Meta Keyword" rows="6" name="meta_keyword" cols="10" maxlength="250"></textarea>
                                </div>
                                <div class="col-md-2">
                                    <label for="title" class="control-label">{{ __("Tags") }}:-<div id="tags" disabled=""></div></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        setTimeout(function(){
            $('tr.page-title:first').trigger('click');
        }, 500);
        
        $(document).on("click",".template-page-detail",function() {
            $('#edit_page_content #content').val('');
            let url = $(this).data('show_url');
            $.get(url,function(response) {
              if(response.status == 'Success'){
                if(response.data){
                    $('#edit_page_content #template_id').val(response.data.id);
                    if(response.data){
                        $('#edit_page_content #tags').html(response.data.tags);
                        $('#edit_page_content #content').val(response.data.content);
                    }else{
                      $(':input:text').val('');
                      $('textarea').val('');
                    }
                }else{
                    $('textarea').val('');
                    $(':input:text').val('');
                    $('#edit_page_content #page_id').val('');
                }
              }
            });
        });
        $(document).on("click","#update_template",function() {
            var update_url = "{{route('cms.agent-sms.template.update')}}";
            let content = $('#edit_page_content #content').val();
            let template_id = $('#edit_page_content #template_id').val();
            var data = {content: content, template_id:template_id};
            $.post(update_url, data, function(response) {
              $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
              setTimeout(function() {
                    location.reload()
                }, 2000);
            }).fail(function(response) {
                
            });
        });
    });
</script>
@endsection
@section('script')

@endsection
