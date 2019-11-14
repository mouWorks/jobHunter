<!DOCTYPE html>
<html lang="zh-TW">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="copyright" content="">
	<title>JobHunter</title>
	<meta property="og:title" content="" />
	<meta property="og:name" content="" />
	<meta property="og:type" content="article" >
	<meta property="og:image" content="" />
	<meta property="og:url" content="" />
	<meta property="og:description" content="" />
	<link rel="canonical" href=""/>
	<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<script type="text/javascript">var console = { log: function() {} };</script>
	<![endif]-->
	<meta http-equiv="X-UA-Compatible" content="IE=10"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta name="format-detection" content="telephone=no"/>
	<link href="{{ URL::asset('css/animate.min.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ URL::asset('css/slick.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ URL::asset('css/slick-theme.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ URL::asset('css/common.css') }}" rel="stylesheet" type="text/css"/>
	<script src="https://www.eztravel.com.tw/events/js/jquery-1.9.1.min.js"></script>
	<script src="{{ URL::asset('js/jquery-ui-1.8.17.custom.min.js')}}"></script>
	<script src="https://www.eztravel.com.tw/events/js/bootstrap.min.js"></script>
	<script src="{{ URL::asset('js/slick.min.js')}}"></script>
	<script src="{{ URL::asset('js/init.js')}}"></script>
	<title>JobFinder</title>

	<!--[if lt IE 9]>
	<link rel="stylesheet" type="text/css" href="../compile/css/ie8.css">
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<script src="../js/3rd-party/respond.js"></script>
	<![endif]-->
</head>

<body>
<header>
	<!--NAV-->
	<nav>
		<div id="menu">
			<span></span>
			<span></span>
			<span></span>
		</div>
		<div id="logo">
			<a href="/"><h1>JobHunter</h1></a>
		</div>
		<ul id="nav">
			<li class="list l1"><a href="/awshack/list/104"><span>104職缺</span></a></li>
			<li class="list l2"><a href="/awshack/list/ptt"><span>PTT職缺</span></a></li>
			<li class="list l3"><a href="/awshack/list/pt"><span>即時打工</span></a></li>
		</ul>
	</nav>
	<!--END NAV-->
</header>
	@yield('content')
	@include('lists/common_js')
	@section('customJs')
	@show

	<div id="btn_top">
		<a href="javascript:;">PAGE TOP</a>
	</div>
	<footer>
		<div id="footer">Copyright © 2019 JobHunter All Rights Reserved.</div>
	</footer>
</body>

</html>
