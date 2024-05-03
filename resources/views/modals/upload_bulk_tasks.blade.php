<div id="upload-bulk-tasks" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">{{__("Upload")}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="submit_bulk_upload_task" method="POST" enctype="multipart/form-data" action="{{route('tasks.importCSV')}}">
                @csrf
                <div class="modal-body px-3 py-0">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <a href="{{ url('file-download/sample_routes.csv') }}">{{ __('Download Sample file here!') }}</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="bulk_upload">
                                <input type="file" accept=".csv" onchange="submitProductImportForm();" data-plugins="dropify" name="bulk_upload_file" class="dropify" />
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('File Name') }}</th>
                                    <th colspan="2">{{ __('Status') }}</th>
                                    <th>{{ __('Link') }}</th>
                                </tr>
                            </thead>
                            <tbody id="post_list">
                                @foreach ($csvRoutes as $csv)
                                    <tr data-row-id="{{ $csv->id }}">
                                        <td> {{ $loop->iteration }}</td>
                                        <td> {{ $csv->name }}</td>
                                        @if ($csv->status == 1)
                                            <td>{{ __('Pending') }}</td>
                                            <td></td>
                                        @elseif($csv->status == 2)
                                            <td>{{ __('Success') }}</td>
                                            <td></td>
                                        @else
                                            <td>{{ __('Errors') }}</td>
                                            <td class="position-relative text-center">
                                                <i class="mdi mdi-exclamation-thick"></i>
                                                <ul class="tooltip_error">
                                                    <?php $error_csv = json_decode($csv->error); ?>
                                                    @foreach ($error_csv as $err)
                                                        <li>
                                                            {{ $err }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        @endif
                                        <td> <a href="{{route('uploadeddownload', $csv->name)}}">{{ __('Download') }}</a> </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>