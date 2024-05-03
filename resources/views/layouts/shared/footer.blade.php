@php
    $image = App\Model\Client::first();
   // $image->name = 'Royo';
@endphp

<!-- spinner Start --> 
{{-- <div class="nb-spinner-main">
    <div class="nb-spinner"></div>
</div> --}}
<!-- spinner Start --> 
<!-- Footer Start --> 
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <script>document.write(new Date().getFullYear())</script> &copy;  <a href="#" target="_blank">{{$image->name??'Royo'}} Apps</a> 
            </div>
            <!-- <div class="col-md-6">
                <div class="text-md-right footer-links d-none d-sm-block">
                    <a href="javascript:void(0);">About Us</a>
                    <a href="javascript:void(0);">Help</a>
                    <a href="javascript:void(0);">Contact Us</a>
                </div>
            </div> -->
        </div>
    </div>
</footer>
<!-- end Footer -->