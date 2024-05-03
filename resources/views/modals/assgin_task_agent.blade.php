<div id="add-assgin-agent-model" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">Assign {{ getAgentNomenclature() }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="submit_assign_agent" method="POST" enctype="multipart/form-data" action="{{route('assign.agent')}}">
                @csrf
                <div class="modal-body px-3 py-0">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="typeInput">
                                {!! Form::label('title', 'Status',['class' => 'control-label']) !!}
                                <select name="agent" id="agent_id" class="form-control">
                                    @foreach ($agents as $item)
                                    @php
                                        $checkAgentActive = ($item->is_available == 1) ? ' ('.__('Online').')' : ' ('.__('Offline').')';
                                    @endphp
                                      <option value="{{$item->id}}">{{ ucfirst($item->name). $checkAgentActive}}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-blue waves-effect waves-light">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>