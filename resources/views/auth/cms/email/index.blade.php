@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Emails'])
@section('css')
<!-- <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet"> -->
<link rel="stylesheet" href="{{ asset('assets/ck_editor/samples/css/samples.css') }}">
<link rel="stylesheet" href="{{ asset('assets/ck_editor/samples/toolbarconfigurator/lib/codemirror/neo.css') }}">
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ __("Emails") }}</h4>
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
                                    <th class="border-bottom-0">{{ __("Templates Name") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($email_templates as $email_template)
                                <tr class="page-title active-page email-page-detail" data-email_template_id="{{$email_template->id}}" data-show_url="{{route('cms.email.template.show', ['id'=> $email_template->id])}}">
                                    <td>
                                        <a class="text-body d-block" href="javascript:void(0)">{{$email_template->label}}</a>
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
        <div class="col-md-7 col-xl-6 mb-2 al_custom_cms_email">
            <div class="card">
                <div class="card-body p-3" id="edit_page_content">
                    <div class="row">
                        <div class="col-12 text-right">
                            <button type="button" class="btn btn-info" id="update_email_template"> {{ __("Publish") }}</button>
                        </div>
                    </div>
                    <div class="row al_custom_cke">
                        <input type="hidden" id="email_template_id" value="">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <label for="title" class="control-label ">{{ __("Subject") }}</label>
                                    <input class="form-control al_box_height" id="subject" placeholder="{{ __("Meta Keyword") }}" name="subject" type="text">
                                    <span class="text-danger error-text updatetitleError"></span>
                                </div>
                                <div class="col-md-10 mb-3">
                                    <label for="title" class="control-label mb-0">{{ __("Content") }}</label>
                                    <textarea style="visibility: hidden;" class="form-control" id="editor" placeholder="{{ __("Meta Keyword") }}" rows="6" name="meta_keyword" cols="10"></textarea>
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
<script src="{{ asset('assets/ck_editor/ckeditor.js')}}"></script>
<script src="{{ asset('assets/ck_editor/samples/js/sample.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        setTimeout(function() {
            $('tr.page-title:first').trigger('click');
        }, 500);
        $(document).on("click", "#client_language", function() {
            $('tr.page-title:first').trigger('click');
        });
        $(document).on("click", ".email-page-detail", function() {
            // $('#edit_page_content #content').val('');
            // $('#edit_page_content #content').summernote('destroy');
            let url = $(this).data('show_url');
            let language_id = $('#edit_page_content #client_language :selected').val();
            $.get(url, function(response) {
                if (response.status == 'Success') {
                    if (response.data) {
                        $('#edit_page_content #email_template_id').val(response.data.id);
                        if (response.data) {
                            $('#edit_page_content #tags').html(response.data.tags);
                            $('#edit_page_content #subject').val(response.data.subject);
                            // $('#edit_page_content #content').val(response.data.content);
                            // $('#edit_page_content #content').summernote({
                            //     'height': 450
                            // });
                            CKEDITOR.instances.editor.setData(response.data.content);
                        } else {
                            $(':input:text').val('');
                            $('textarea').val('');
                        }
                    } else {
                        $('textarea').val('');
                        $(':input:text').val('');
                        $('#edit_page_content #page_id').val('');
                    }
                }
            });
        });
        $(document).on("click", "#update_email_template", function() {
            var update_url = "{{route('cms.email.template.update')}}";
            let subject = $('#edit_page_content #subject').val();
            // let content = $('#edit_page_content #content').val();
            let content = CKEDITOR.instances.editor.getData();
            let email_template_id = $('#edit_page_content #email_template_id').val();
            var data = {
                subject: subject,
                content: content,
                email_template_id: email_template_id
            };
            $.post(update_url, data, function(response) {
                $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                setTimeout(function() {
                    location.reload()
                }, 2000);
            }).fail(function(response) {
                $('#edit_page_content .updatetitleError').html(response.responseJSON.errors.edit_title[0]);
                $('#edit_page_content .updatedescrpitionError').html(response.responseJSON.errors.edit_description[0]);
            });
        });
    });
</script>
@endsection
@section('script')
<!-- <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script>
    CKEDITOR.replace('editor');
    CKEDITOR.config.height = 450;
</script>
@endsection
