<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
	<title>@yield('title')</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" />
	<!--begin::Fonts-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<!--end::Fonts-->
	<!--begin::Global Stylesheets Bundle(used by all pages)-->
	<link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
	<!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="bg-body">
	<!--begin::Main-->
	<div class="d-flex flex-column flex-root">
		<!--begin::Authentication - Sign-in -->
		<div class="d-flex flex-column flex-lg-row flex-column-fluid">
			<!--begin::Aside-->
			<div class="d-flex flex-column flex-lg-row-auto w-xl-600px positon-xl-relative"
				style="background-color: #F2C98A">
				<!--begin::Wrapper-->
				<div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-600px scroll-y">
					<!--begin::Content-->
					<div class="d-flex flex-row-fluid flex-column text-center p-10 pt-lg-20">
						<!--begin::Logo-->
						<a href="../../demo8/dist/index.html" class="py-9 mb-5">
							<img alt="Logo" src="{{ asset('assets/media/logos/logoMH1.png') }}" class="h-60px" />
						</a>
						<!--end::Logo-->
						<!--begin::Title-->
						<h1 class="fw-bolder fs-2qx pb-5 pb-md-10" style="color: #986923;">Welcome to Browser Antidetect
						</h1>
						<!--end::Title-->
						<!--begin::Description-->
						<!-- <p class="fw-bold fs-2" style="color: #986923;">Discover Amazing Metronic<br />with great build tools</p> -->
						<!--end::Description-->
					</div>
					<!--end::Content-->
					<!--begin::Illustration-->
					<div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-100px min-h-lg-350px"
						style="background-image: {{asset('assets/media/illustrations/sketchy-1/13.png')}}"></div>
					<!--end::Illustration-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Aside-->
			<!--begin::Body-->
			<div class="d-flex flex-column flex-lg-row-fluid py-10">
				<!--begin::Content-->
				@yield('content')
				<!--end::Content-->
				<!--begin::Footer-->
				<div class="d-flex flex-center flex-wrap fs-6 p-5 pb-0">
					{{--
					<!--begin::Links-->
					<div class="d-flex flex-center fw-bold fs-6">
						<a href="https://keenthemes.com" class="text-muted text-hover-primary px-2"
							target="_blank">About</a>
						<a href="https://keenthemes.com/support" class="text-muted text-hover-primary px-2"
							target="_blank">Support</a>
						<a href="https://1.envato.market/EA4JP" class="text-muted text-hover-primary px-2"
							target="_blank">Purchase</a>
					</div>
					<!--end::Links--> --}}
				</div>
				<!--end::Footer-->
			</div>
			<!--end::Body-->
		</div>
		<!--end::Authentication - Sign-in-->
	</div>
	<!--end::Main-->
	<script>
		var hostUrl = "assets/";
	</script>
	<!--begin::Javascript-->
	<!--begin::Global Javascript Bundle(used by all pages)-->
	<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
	<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
	<!--end::Global Javascript Bundle-->
	<!--begin::Page Custom Javascript(used by this page)-->
	{{-- <script src="assets/js/custom/authentication/sign-in/general.js"></script> --}}
	<!--end::Page Custom Javascript-->
	<!--end::Javascript-->
	@include('auth.layouts.js')
</body>
<!--end::Body-->

</html>