
{{-- <button type="button" class="btn btn-primary m-2" data-toggle="modal" data-target="#commonModal">Click Here</button> --}}
<div class="modal fade commonModal" id="@yield('popup-id')"  role="dialog" aria-labelledby="@yield('popup-id')Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="@yield('popup-id')Label"> @yield('popup-header')</h4>
          <button type="button" class="close" data-dismiss="modal" aria- label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body"> 
          <div id="modalContent">
              @yield('popup-content')
          </div>  
        </div>
        <div class="modal-footer">
          <button style="width: auto" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
        </div>
      </div>
    </div>
  </div>