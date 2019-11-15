@extends('AwsHack/main')@section('content')	<div id="wrapper">		<!-- BANNER -->		<div id="banner">			<div class="banner">				<span class="content_title"><h2>PTT職缺</h2></span>			</div>		</div>		<!--END BANNER-->		<!-- 104職缺 -->		<section id="page_content">			<div class="content">				<ul class="product_detail">					<!--職缺詳細資訊-->					<li class="col-md-9 col-sm-9 col-xs-12 bootstrap_default product_detail_info">						<ul>							<li class="product_info_title">{{$job['title']}}</li>							<li class="product_info_company">								<ul>									<li class="col-md-10 col-sm-9 col-xs-9 bootstrap_default product_info_cdes">										<span>{{$job['company_name']}}</span>									</li>								</ul>							</li>							<li class="product_info_list">								<div>職缺內容</div>								{!! $job['description'] !!}							</li>							<li class="btn btn_apply">								<a href="{{ $job['source_url'] }}" class="orange02" target="_blank">馬上應徵</a>							</li>						</ul>					</li>					<!--END 職缺詳細資訊-->					<!--職缺重點-->					<li class="col-md-3 col-sm-3 col-xs-12 bootstrap_default product_detail_key">						<ul>							<li class="product_key_title">{{ $job['title'] }}</li>							<li class="product_key_list">{{ $job['region'] }}</li>							<li class="product_key_list">月薪 {{ $job['salary'] }}</li>							<li class="btn product_key_more"><a href="{{ $job['source_url'] }}" class="orange02" target="_blank">馬上應徵</a></li>						</ul>					</li>					<!--END 職缺重點-->				</ul>				<div class="btn btn_back">					<a onclick="window.history.back()" class="orange">BACK</a>				</div>			</div>		</section>		<!--END 104職缺-->	</div>	<!--END WRAPPER-->@stop@section('customJs'){{--	<script src="{{ URL::asset('/js/3rd-party/jquery.history.js') }}" charset="utf-8"></script>--}}{{--	<script src="{{ URL::asset('/js/lists/job.js') }}" charset="utf-8"></script>--}}@stop