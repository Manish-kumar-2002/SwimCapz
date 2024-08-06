
<style>
@import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wdth,wght@0,75..100,300..800;1,75..100,300..800&display=swap');
.email-wrap{
    padding: 0 20px;
	min-width: 300px;
	background-color: #fffffe;
	color: #1a1a1a;
	text-align: center;
	word-wrap: break-word;
	-webkit-font-smoothing: antialiased;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-direction: column;
    margin:0;
    height: 100vh;
    font-family: "Open Sans", sans-serif;
}
a:link,
a:visited {
	color: #00c2a8;
}
a:hover,
a:active {
	color: #03a994;
}



    .site-header {
	margin: 0 auto;
	padding: 80px 0 0;
	max-width: 820px;
}
.site-header__title {
    font-family: "Open Sans", sans-serif;
	margin: 0;
	font-size: 2.5rem;
	font-weight: 700;
	line-height: 1.1;
	text-transform: uppercase;
	-webkit-hyphens: auto;
	-moz-hyphens: auto;
	-ms-hyphens: auto;
	hyphens: auto;
}
.main-content {
	margin: 0 auto;
	max-width: 820px;
}
.main-content__checkmark {
	font-size: 4.0625rem;
	line-height: 1;
	color: #24b663;
}
.main-content__body {
	margin: 20px 0 0;
	font-size: 1rem;
	line-height: 1.4;
}   
.site-footer {
	margin: 0 auto;
	padding: 80px 0 25px;
	padding: 0;
	max-width: 820px;
}
.site-footer__fineprint {
	font-size: 0.9375rem;
	line-height: 1.3;
	font-weight: 400;
}
@media only screen and (min-width: 40em) {
	.site-header {
		padding-top: 80px;
	}
	.site-header__title {
		font-size: 6.25rem;
	}
	.main-content__checkmark {
		font-size: 9.75rem;
	}
	.main-content__body {
		font-size: 1.25rem;
	}
	.site-footer {
		padding: 145px 0 25px;
	}
	.site-footer__fineprint {
		font-size: 1.125rem;
	}
}
</style>
<div class="email-wrap">
<div class="header-email">
<header class="site-header" id="header">
    <div class="logo-wrap">
		<a class="navbar-brand" href="{{ route('front.index') }}">
			<img class="nav-logo lazy" src="{{ asset('assets/images/' . $gs->logo) }}"
				alt="Img not found !"></a>
    </div>
		<h1 class="site-header__title" data-lead-id="site-header-title">THANK YOU!</h1>
	</header>

	<div class="main-content">
		<i class="fa fa-check main-content__checkmark" id="checkmark"></i>
		<p class="main-content__body" data-lead-id="main-content-body">Your email address has been successfully confirmed. <br>You can now log in to your SwimCapz account to continue. </p>
	</div>
</div>

	<footer class="site-footer" id="footer">
		<p class="site-footer__fineprint" id="fineprint">Copyright ©2024 | All Rights Reserved</p>
	</footer>
</div>