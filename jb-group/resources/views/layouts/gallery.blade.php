<link href="{{asset('assets/plugins/images/bootstrap/4.1.1/bootstrap.min.css')}}" rel="stylesheet" id="bootstrap-css">
<script src="{{asset('assets/plugins/images/bootstrap/4.1.1/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/plugins/images/jquery/3.2.1/jquery.min.js')}}"></script>


<link rel="stylesheet" href="{{asset('assets/plugins/images/magnific-popup.css')}}"/>
<script src="{{asset('assets/plugins/images/isotope.pkgd.js')}}"></script>
<script src="{{asset('assets/plugins/images/jquery.magnific-popup.js')}}"></script>
<!------ Include the above in your HEAD tag ---------->

<style>
    a, a:hover {
        color: white;
    }

    .portfolio-menu ul li {
        display: inline-block;
        margin: 0;
        list-style: none;
        padding: 10px 15px;
        cursor: pointer;
        -webkit-transition: all 05s ease;
        -moz-transition: all 05s ease;
        -ms-transition: all 05s ease;
        -o-transition: all 05s ease;
        transition: all .5s ease;
    }

    .portfolio-item .item {
        float: left;
        margin-bottom: 10px;
    }

</style>
<div id="images" class="portfolio-item row">
    @foreach($images as $image)
        @if(!str_contains($image->format, 'video'))
            <div class="item selfie col-lg-3 col-md-4 col-6 col-sm">
                <a href="/images/{{$image->album->name}}/{{$image->name}}"
                   class="fancylight popup-btn" data-fancybox-group="light">
                    <img class="img-fluid"
                         src="/images/{{$image->album->name}}/{{$image->name}}"
                         alt="">
                </a>
            </div>
        @endif
    @endforeach
</div>


<script>
    $('.portfolio-menu ul li').click(function () {
        $('.portfolio-menu ul li').removeClass('active');
        $(this).addClass('active');

        var selector = $(this).attr('data-filter');
        $('.portfolio-item').isotope({
            filter: selector
        });
        return false;
    });
    $(document).ready(function () {
        var popup_btn = $('.popup-btn');
        popup_btn.magnificPopup({
            type: 'image',
            gallery: {
                enabled: true
            }
        });
    });
</script>
