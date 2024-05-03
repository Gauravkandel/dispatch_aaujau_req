<!doctype html>
<html lang="en">

<head>
   <!-- Required meta tags -->
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name=”format-detection” content=”telephone=no”>
   <link rel="shortcut icon" type="image/x-icon" href="{{$favicon ?? ''}}">
   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
   <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="{{asset('tracking/css/bootstrap.css')}}">
   <link rel="stylesheet" href="{{asset('tracking/css/style.css')}}">
   <link rel="stylesheet" href="{{asset('tracking/css/responsive.css')}}">
   <title> Order Feedback</title>
</head>

<body>

   <!--location Area -->
   <section class="feedback_box py-5">
     <form action="{{route('feedbackSave')}}" method="POST" id="feedback">
        <div class="container">
          <div class="row">
              <div class="col-12">
                <ul class="top_btns d-flex align-items-center justify-content-between">
                    <li><a class="cancel_btn" href="#">Cancel</a></li>
                    <li><a class="feedback_btn" href="#">Feedback</a></li>
                    <li><a class="submit_btn anchor_submit" href="#" id="click">Submit</a></li>
                </ul>
              </div>
              @csrf
              <input type="hidden" name="unique_id" value="{{$id}}">
              <input type="hidden" name="client_code" value="{{$user}}">
              <div class="col-12 text-center my-4">
                <p>Rate your experiences with Urban Deliveries</p>
                <fieldset class="rating">
                    <input type="radio" id="star5" name="rating" value="5" /><label class="full" for="star5" title="Awesome - 5 stars"></label>
                    <input type="radio" id="star4half" name="rating" value="4.5" /><label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
                    <input type="radio" id="star4" name="rating" value="4" /><label class="full" for="star4" title="Pretty good - 4 stars"></label>
                    <input type="radio" id="star3half" name="rating" value="3.5" /><label class="half" for="star3half" title="Meh - 3.5 stars"></label>
                    <input type="radio" id="star3" name="rating" value="3" /><label class="full" for="star3" title="Meh - 3 stars"></label>
                    <input type="radio" id="star2half" name="rating" value="2.5" /><label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
                    <input type="radio" id="star2" name="rating" value="2" /><label class="full" for="star2" title="Kinda bad - 2 stars"></label>
                    <input type="radio" id="star1half" name="rating" value="1.5" /><label class="half" for="star1half" title="Meh - 1.5 stars"></label>
                    <input type="radio" id="star1" name="rating" value="1" /><label class="full" for="star1" title="Sucks big time - 1 star"></label>
                    <input type="radio" id="starhalf" name="rating" value="0.5" /><label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
                </fieldset>
              </div>
              <div class="col-12">
                <textarea class="feedback_txt p-2" name="" id="" cols="30" rows="10" name="review" placeholder="Fast delivery and Greg was super friendly!"></textarea>
              </div>
          </div>
        </div>
      </form>
   </section>



   <!-- jQuery first, then Popper.js, then Bootstrap JS -->
   <script src="{{asset('tracking/js/jquery-min.js')}}"></script>
   <script src="{{asset('tracking/js/common.js')}}"></script>
   <script src="{{asset('tracking/js/bootstrap.min.js')}}"></script>
   <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</body>

</html>
