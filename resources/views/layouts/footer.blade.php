<div id="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-sm-12">
                <img src="{{ asset('assets/image/MicrosoftTeams-image.png') }}" alt="" class="mb-4">
                <h2>Bizboo Sdn Bhd.</h2>
            </div>
            <div class="col-lg-3 mid-screen">
                <h2 class="ft-title">{!! trans('labels.quick_link') !!}</h2>
                <a class="link d-inline-block f-color" href="{{ route('home', '#about') }}">{!! trans('labels.menu.about_us') !!}</a><br>
                <a class="link d-inline-block f-color" href="{{ route('home', '#feature') }}">{!! trans('labels.menu.feature') !!}</a><br>
                <a class="link d-inline-block f-color" href="{{ route('home', '#contact') }}">{!! trans('labels.menu.contact_us') !!}</a><br>
                <a class="link d-inline-block f-color" href="{{ route('term-condition') }}">{!! trans('labels.menu.terms_service') !!}</a><br>
                <a class="link d-inline-block f-color" href="{{ route('privacy-policy') }}">{!! trans('labels.menu.privacy_policy') !!}</a><br>
                <a class="link d-inline-block f-color" href="{{ route('merchant.register') }}">{!! trans('labels.menu.join_as_merchant') !!}</a>
            </div>
            <div class="footer col-lg-4 pr-5 mid-screen">
                <h2>{!! trans('labels.address') !!}</h2>
                <p class="f-color pr-5">
                    A-8-10, Trefoil @ Setia City, No.2, Jalan Setia Dagang Ah U13/Ah, Setia Alam Section U13, 40170 Shah Alam, Selangor.
                </p>
            </div>
            <div class="footer col-lg-2 mid-screen">
                <h2 class="ft-title">{!! trans('labels.contact_no') !!}</h2>
                <p class="f-color mb-3">03-3488002</p>
                <h2 class="ft-title">{!! trans('labels.follow_us') !!}</h2>
                <div>
                    <a target="_blank" href="https://www.facebook.com/"><img src="{{ asset('assets/image/Group 124.svg') }}" alt="" class="social mr-4"></a>
                    <a target="_blank" href="https://www.instagram.com/"><img src="{{ asset('assets/image/Group 126.svg') }}" alt="" class="social"></a>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="copyright" class="py-4">
    <div class="container">
        <p class="copyright-adjust">{!! __('labels.copyright') !!}</p>
    </div>
</div>