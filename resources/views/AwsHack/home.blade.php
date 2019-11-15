@extends('AwsHack/main')@section('content')	<div id="wrapper">		<!-- BANNER -->		<div id="i_banner">			<ul class="banner">				<ul class="i_search">					<li>						{!! $location_select_box !!}					</li>					<li>						<form class="search_form" action="">							<div class="vessel">								<label>検索:</label>								<input type="text" id="q" placeholder="關鍵字(例：職稱、公司名、技能專長...)">							</div>						</form>					</li>					<li class="btn btn_search">						<a href="javascript:;" id="submit" class="orange">搜尋104職缺<input type="submit" value=""></a>					</li>				</ul>			</ul>		</div>		<!--END BANNER-->		<!-- 最新104職缺 -->		<section id="company_job">			<div class="content">				<div class="content_title"><h2>最新104職缺</h2></div>				<div class="main_product center">					@foreach ($jobs['104'] as $job)					<div class="main_product_list">							<ul>								<li class="main_product_key">									<span class="col-md-4 col-sm-4 col-xs-4 bootstrap_default">{!!$job['location']!!}</span>									<span class="col-md-4 col-sm-4 col-xs-4 bootstrap_default">月薪<br/>{{$job['salary']}}</span>									<span class="col-md-4 col-sm-4 col-xs-4 bootstrap_default">1年以上<br/>大學</span>								</li>								<li class="main_product_title">{{$job['title']}}</li>								<li class="main_product_company">									<ul>										@if (!empty($job['img']))										<li class="col-md-2 col-sm-3 col-xs-3 bootstrap_default main_product_img"><img src="{{$job['img']}}" alt=""></li>										@endif										<li class="col-md-10 col-sm-9 col-xs-9 bootstrap_default main_product_cdes">											<span>{{$job['company']['name']}}</span>											<p>{{$job['company']['indcat']}}												<br/>{{$job['company']['capital'] ?? ''}}												<br/>員工{{$job['company']['employees'] ?? ''}}											</p>										</li>									</ul>								</li>								<li class="main_product_jdes">{!! $job['description'] !!}</li>								<li class="main_product_date">更新日期：{{$job['date']}}</li>							</ul>						<div class="main_product_more">							<span class="col-md-6 col-sm-6 col-xs-6 bootstrap_default btn btn_more"><a href="{{$job['internal_url']}}" class="orange">詳細資訊</a></span>							<span class="col-md-6 col-sm-6 col-xs-6 bootstrap_default btn btn_apply"><a href="javascript:;" class="orange02" target="_blank">馬上應徵</a></span>						</div>					</div>					@endforeach				</div>				<div class="btn btn_more">					<a href="list/104" class="orange">MORE</a>				</div>			</div>		</section>		<!--END 最新104職缺-->		<!-- 最新PTT職缺 -->		<section id="ptt_job">			<div class="content">				<div class="content_title"><h2>最新PTT職缺</h2></div>				<div class="main_product center">					@foreach ($jobs['ptt'] as $job)					<div class="main_product_list">						<ul>							<li class="main_product_key">								<span class="col-md-6 col-sm-6 col-xs-6 bootstrap_default">{!!$job['location']!!}</span>								<span class="col-md-6 col-sm-6 col-xs-6 bootstrap_default">月薪<br/>{{$job['salary']}}</span>							</li>							<li class="main_product_title">{{$job['title']}}</li>							<li class="main_product_jdes">{{$job['description']}}</li>							<li class="main_product_date">更新日期：{{$job['date']}}</li>						</ul>						<div class="main_product_more">							<span class="col-md-6 col-sm-6 col-xs-6 bootstrap_default btn btn_more"><a href="{{$job['internal_url']}}" class="orange">詳細資訊</a></span>							<span class="col-md-6 col-sm-6 col-xs-6 bootstrap_default btn btn_apply"><a href="{{$job['external_url']}}" class="orange02" target="_blank">馬上應徵</a></span>						</div>					</div>					@endforeach				</div>				<div class="btn btn_more">					<a href="list/ptt" class="orange">MORE</a>				</div>			</div>		</section>		<!--END 最新PTT職缺-->		<!-- 最新打工資訊 -->		<section id="ptt_job">			<div class="content">				<div class="content_title"><h2>最新打工資訊</h2></div>				<div class="main_product center">					@foreach ($jobs['part_time'] as $job)					<div class="main_product_list">						<ul>							<li class="main_product_key">								<span class="col-md-4 col-sm-4 col-xs-4 bootstrap_default">{{$job['location']}}</span>								<span class="col-md-4 col-sm-4 col-xs-4 bootstrap_default">時薪<br/>{{$job['salary']}}</span>								<span class="col-md-4 col-sm-4 col-xs-4 bootstrap_default">{{$job['time']}}</span>							</li>							<li class="main_product_title">{{$job['title']}}</li>							<li class="main_product_jdes">{{$job['description']}}</li>							<li class="main_product_date">更新日期：{{$job['date']}}</li>						</ul>						<div class="main_product_more">							<span class="col-md-12 col-sm-12 col-xs-12 bootstrap_default btn btn_more"><a href="{{$job['internal_url']}}" class="orange">詳細資訊</a></span>						</div>					</div>					@endforeach				</div>				<div class="btn btn_more">					<a href="list/pt" class="orange">MORE</a>				</div>			</div>		</section>		<!--END 最新打工資訊-->	</div>	<!--END WRAPPER-->	<script type="text/javascript">		$(document).on('ready', function() {			$('.center').slick({				slidesToShow: 2,				slidesToScroll: 1,				dots: true,				arrows: true,				// autoplay: true,				autoplaySpeed: 4000,				responsive: [					{						breakpoint: 1024,						settings: {							arrows: false,						}					},					{						breakpoint: 768,						settings: {							arrows: false,						}					},					{						breakpoint: 480,						settings: {							arrows: false,							slidesToShow: 1,							slidesToScroll: 1,						}					}				]			});		});	</script>@stop