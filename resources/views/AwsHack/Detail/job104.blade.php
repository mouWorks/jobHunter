@extends('AwsHack/main')@section('content')	<div id="wrapper">		<!-- BANNER -->		<div id="banner">			<div class="banner">				<span class="content_title"><h2>104職缺</h2></span>			</div>		</div>		<!--END BANNER-->		<!-- 104職缺 -->		<section id="page_content">			<div class="content">				<ul class="product_detail">					<!--職缺詳細資訊-->					<li class="col-md-9 col-sm-9 col-xs-12 bootstrap_default product_detail_info">						<ul>							<li class="product_info_date">更新日期：<span class="job_date"></span><!-- 2019-10-30 --></li>							<li class="product_info_title job_title"><!-- 設計實習生（Design Intern）- 尋找桌遊愛好者 --></li>							<li class="product_info_company">								<ul>									<li class="col-md-2 col-sm-3 col-xs-3 bootstrap_default product_info_img" style="display:none"><img alt=""></li>									<li class="col-md-10 col-sm-9 col-xs-9 bootstrap_default product_info_cdes">										<span class="job_company_name"><!-- 可樂旅遊_康福旅行社股份有限公司 --></span>										<p><span class="job_company_indcat"></span>{{--旅遊服務業--}}											<br/><span class="job_company_capital"></span>{{--資本額12億8000萬元--}}											<br/>員工<span class="job_company_employees"></span>{{--員工1000--}}										</p>									</li>								</ul>							</li>							<li class="product_info_list">								<div>工作內容</div>								<span class="job_description"></span>								{{--<ul>									<li>【德勤太平洋企業管理咨詢 2020寒假實習徵才】開跑囉！！！										<br/>我們正在尋找有潛力、充滿激情的年輕人才加入我們的實習生計畫。										<br/>我們的實習生計畫會讓您充份展現才華，發揮天賦和潛能。您將與專業的顧問團隊們一起工作，他們會鼓勵您並幫助您在管理顧問工作的實務操作上得到專業的指導。透過親身經歷我們的高價值(high-value)、高科技(high-tech)、高體驗(high-touch)的文化，您將更進一步了解我們。									</li>								</ul>--}}							</li>							<li class="product_info_list others" style="display:none">								<div>加分條件</div>								<span class="job_others"></span>							</li>							<li class="product_info_list welfare" style="display:none">								<div>公司福利</div>								<span class="job_welfare"></span>							</li>							<li class="btn btn_apply">								<a href="javascript:;" class="orange02" target="_blank">馬上應徵</a>							</li>						</ul>					</li>					<!--END 職缺詳細資訊-->					<!--職缺重點-->					<li class="col-md-3 col-sm-3 col-xs-12 bootstrap_default product_detail_key">						<ul>							<li class="product_key_title job_title"></li>							<li class="product_key_list job_region"></li>							<li class="product_key_list job_salary"></li>							<li class="btn product_key_more"><a href="javascript:;" class="orange02" target="_blank">馬上應徵</a></li>						</ul>					</li>					<!--END 職缺重點-->				</ul>				<div class="btn btn_back">					<a onclick="window.history.back()" class="orange">BACK</a>				</div>			</div>		</section>		<!--END 104職缺-->	</div>	<!--END WRAPPER-->	<script>		$(document).ready(function (){			let jobStorage = window.localStorage.getItem('job_storage');			jobStorage = JSON.parse(jobStorage);			console.log(jobStorage);			$('.job_company_name').html(jobStorage.company.name);			$('.job_company_indcat').html(jobStorage.company.indcat);			$('.job_company_capital').html(jobStorage.company.capital);			$('.job_company_employees').html(jobStorage.company.employees);			$('.job_title').html(jobStorage.title);			$('.job_date').html(jobStorage.date);			$('.job_region').html(jobStorage.location);			$('.job_salary').html(jobStorage.salary);			$('.job_description').html(jobStorage.description);			console.log(jobStorage.img);			if (jobStorage.img !== null) {				$('.product_info_img img').attr('src', jobStorage.img);				$('.product_info_img').show();			}			if (jobStorage.others !== '') {				$('.product_info_list.others').show();				$('.product_info_list.others .job_others').html(jobStorage.others);			}			if (jobStorage.welfare !== '') {				$('.product_info_list.welfare').show();				$('.product_info_list.welfare .job_welfare').html(jobStorage.welfare);			}		});	</script>@stop@section('customJs'){{--	<script src="{{ URL::asset('/js/3rd-party/jquery.history.js') }}" charset="utf-8"></script>--}}{{--	<script src="{{ URL::asset('/js/lists/job.js') }}" charset="utf-8"></script>--}}@stop