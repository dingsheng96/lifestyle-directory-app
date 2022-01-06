<div id="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-3 col-sm-12">
                <img src="{{ asset('assets/web/MicrosoftTeams-image.png') }}" alt="" class="row mb-4">
                <h2 class="row">Bizboo Sdn Bhd.</h2>
            </div>
            <div class="col-md-6 col-lg-3">
                <h2>{!! trans('labels.quick_link') !!}</h2>
                <a class='d-inline-block f-color' href="./index.html#about">{!! trans('labels.about_us') !!}</a><br>
                <a class='d-inline-block f-color' href="./index.html#feature">{!! trans('labels.feature') !!}</a><br>
                <a class='d-inline-block f-color' href="./index.html#contact">{!! trans('labels.contact_us') !!}</a><br>
                <a class='d-inline f-color' href="{{ route('term-condition') }}">{!! trans('labels.terms_service') !!}</a><br>
                <a class='d-inline f-color' href="{{ route('privacy-policy') }}">{!! trans('labels.privacy_policy') !!}</a>
            </div>
            <div class="col-md-6 col-lg-4 mid-screen">
                <h2>{!! trans('labels.address') !!}</h2>
                <p class='f-color pr-5'>
                    A-8-10, Trefoil @ Setia City, No.2, Jalan Setia Dagang Ah U13/Ah, Setia Alam Section U13, 40170 Shah Alam, Selangor.
                </p>
            </div>
            <div class="col-md-6 col-lg-2 mid-screen">
                <h2>{!! trans('labels.contact_no') !!}</h2>
                <p class=' f-color mb-3'>03-3488002</p>
                <h2>{!! trans('labels.follow_us') !!}</h2>
                <div>
                    <a target="_blank" href="https://www.facebook.com/"><img src="{{ asset('assets/web/Group 124.svg') }}" alt="" class="social mr-4"></a>
                    <a target="_blank" href="https://www.instagram.com/"><img src="{{ asset('assets/web/Group 126.svg') }}" alt="" class="social"></a>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="copyright" class="pt-3 ">
    <div class="container">
        <p>{!! __('labels.copyright') !!}</p>
    </div>
</div>