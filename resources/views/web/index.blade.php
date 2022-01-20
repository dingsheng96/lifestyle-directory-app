@extends('layouts.master')

@section('content')

<section id="home" class="position-relative">
    <div class="container">
        <div class="row align-items-center p-0 ">
            <div class="col-xl-5 col-lg-6 col-md-12 mb-5 home-text">
                <div class='no-pad'>
                    <img src="{{ asset('assets/image/logo.png') }}" alt="">
                    <h2 class="home-h2">{{ trans('labels.home.main_title') }}</h2>
                    <p class="home-p pr-5 no-pad">{{ trans('labels.home.sub_title') }}</p>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 small-res">
                            <a href="{{ env('APPLE_APP_STORE_LINK') }}" target="_blank">
                                <img src="{{ asset('assets/image/Appstore.png') }}" class="img-fluid">
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 small-res">
                            <a href="{{ env('ANDROID_PLAY_STORE_LINK') }}" target="_blank">
                                <img src="{{ asset('assets/image/Google-playstore.png') }}" class="img-fluid">
                            </a>
                        </div>
                        {{-- <div class="col-lg-4 col-md-4 small-res">
                            <a href="{{ env('HUAWEI_APP_GALLERY_LINK') }}">
                                <img src="{{ asset('assets/image/Huawei Gallery.png') }}" class="img-fluid">
                            </a>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="ml-auto px-0 ">
                <img class="home-img" src="{{ asset('assets/image/home_img.png') }}" alt="">
            </div>
        </div>
    </div>
</section>

<div class="home-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-4 small-res no-pad">
                <h1 class="text-white text-center">150,000</h1>
                <p class="text-white text-center text">{{ trans('labels.home.stat_label_downloads') }}</p>
            </div>
            <div class="col-md-12 col-lg-4 small-res no-pad">
                <h1 class="text-white text-center">5000+</h1>
                <p class="text-white text-center text">{{ trans('labels.home.stat_label_merchants') }}</p>
            </div>
            <div class="col-md-12 col-lg-4 small-res no-pad">
                <h1 class="text-white text-center">200%</h1>
                <p class="text-white text-center text">{{ trans('labels.home.stat_label_sales') }}</p>
            </div>
        </div>
    </div>
</div>

<section id="about">
    <div class="container">
        <div class="row">
            <div class="col about-img">
                <div class="col-xl-6 col-lg-9 col-md-11 about-space pr-5">
                    <div class="text-left about-box p-5">
                        <h2 class="sub">{{ trans('labels.about_us.sub_title') }}</h2>
                        <h1>{{ trans('labels.about_us.main_title') }}</h1>
                        <div class="pr-4 no-pad">
                            <p>{{ trans('labels.about_us.content_1') }}</p><br>
                            <p>{{ trans('labels.about_us.content_2') }}</p><br>
                            <p>{{ trans('labels.about_us.content_3') }}</p><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="feature">
    <div class="container">
        <h2 class="sub">{{ trans('labels.feature.sub_title') }}</h2>
        <h1>{{ trans('labels.feature.main_title') }}</h1>
        <div class="row text-center ">
            <div class="col-xl-4 col-lg-6 col-md-12 py-5 pr-5 card-adjust">
                <div class="card h-100 py-4">
                    <div class="card-body px-5">
                        <img src="{{ asset('assets/image/Group 980.svg') }}" alt="">
                        <h2 class="card-h2">{{ trans('labels.feature.item_1.title') }}</h2>
                        <p class='card-h text-center'>{{ trans('labels.feature.item_1.content') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-12 py-5 pr-5 card-adjust">
                <div class="card h-100 py-4">
                    <div class="card-body px-5">
                        <img src="{{ asset('assets/image/Group 981.svg') }}" alt="">
                        <h2 class="card-h2">{{ trans('labels.feature.item_2.title') }}</h2>
                        <p class='card-h text-center'>{{ trans('labels.feature.item_2.content') }}</p>
                        <p>&nbsp;</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 py-5 pr-5 card-adjust">
                <div class="card h-100 py-4">
                    <div class="card-body px-5">
                        <img src="{{ asset('assets/image/Group 982.svg') }}" alt="">
                        <h2 class="card-h2">{{ trans('labels.feature.item_3.title') }}</h2>
                        <p class='card-h text-center'>{{ trans('labels.feature.item_3.content') }}</p>
                        <p>&nbsp;</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if (isset($categories) && $categories->count() > 0)
<section id="industry">
    <h2 class="sub text-center i-h2">{{ trans('labels.our_industry.main_title') }}</h2>
    <div class="container">
        <div class="row ">
            @forelse ($categories as $category)
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <a href="">
                    <div class="position-relative">
                        <div class="overlay"></div>
                        <img src="{{ $category->media->full_file_path }}" alt="" class="img-fluid res-img">
                        <p class="image-text ">{{ $category->name }}</p>
                    </div>
                </a>
            </div>
            @empty
            @endforelse
        </div>
    </div>
</section>
@endif

<section id="contact">
    <div class="container mb-5">
        <div class="row">
            <div class="col-md-12 col-lg-6">
                <img src="{{ asset('assets/image/Mask Group 14.png') }}" alt="" class="img-fluid">
            </div>
            <div class="col">
                <h2 class="sub">{{ trans('labels.contact_us.sub_title') }}</h2>
                <h1 class="mb-4">{{ trans('labels.contact_us.main_title') }}</h1>
                <form action="{{ route('contact_us') }}" role="form" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group-lg mb-4">
                        <label for="name" class="label-text required">{{ trans('labels.contact_us.contact_form.name') }}</label>
                        <div class="form-row" id="name">
                            <div class="col-lg-6 input-margin">
                                <input type="text" class="form-control form-control-lg @error('first_name') is-invalid @enderror" id="fName" name="first_name" placeholder="{{ trans('labels.contact_us.contact_form.first_name') }}" required>
                                @error('first_name')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control form-control-lg @error('last_name') is-invalid @enderror" id="lName" name="last_name" placeholder="{{ trans('labels.contact_us.contact_form.last_name') }}" required>
                                @error('last_name')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group-lg mb-4">
                        <label for="email" class="label-text required">{{ trans('labels.contact_us.contact_form.email') }}</label>
                        <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" name="email" placeholder="Eg: example@email.com" required>
                        @error('email')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                    <div class="form-group-lg mb-4">
                        <label for="phone_no" class="label-text required">{{ trans('labels.contact_us.contact_form.phone') }}</label>
                        <input type="text" class="form-control form-control-lg phone_format @error('phone_no') is-invalid @enderror" id="phone_no" name="phone_no" placeholder="Eg: +6012xxxxxxx" pattern="^(\+601)[0-46-9][0-9]{7,8}$" required>
                        @error('phone_no')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                    <div class="form-group-lg mb-4">
                        <label for="message" class="label-text">{{ trans('labels.contact_us.contact_form.message') }} ({{ trans('labels.contact_us.contact_form.optional') }})</label>
                        <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="6" placeholder="Please enter your comments."></textarea>
                        @error('message')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                    <button class="btn btn-attr" type="submit">{{ trans('labels.contact_us.contact_form.submit') }}</button>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
@if (Session::has('send_mail_success'))
<script type="text/javascript">
    $(window).on('load', function () {
        alert("{{ Session::get('send_mail_success') }}");
    });
</script>
@elseif(Session::has('send_mail_fail'))
<script type="text/javascript">
    $(window).on('load', function () {
        alert("{{ Session::get('send_mail_fail') }}");
    });
</script>
@endif
@endpush