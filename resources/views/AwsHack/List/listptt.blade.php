@extends('AwsHack/main')@section('content')	<div id="wrapper">		<!-- BANNER -->		<div id="banner">			<div class="banner">				<span class="content_title"><h2>PTT職缺</h2></span>				<ul class="search">					<li>						{!! $location_select_box !!}					</li>					<li>						<form class="search_form" action="">							<div class="vessel">								<label>検索:</label>								<input type="text" id="q" value="{{ $q }}" placeholder="關鍵字(例：職稱、公司名、技能專長...)">								<input id="submit" type="submit" value="">							</div>						</form>					</li>				</ul>			</div>		</div>		<!--END BANNER-->		<!-- 104職缺 -->		<section id="company_job">			<div class="content">				<div class="main_product center">					<div class="col-md-6 col-sm-6 col-xs-12 bootstrap_default main_product_list">						<ul>							<li class="main_product_key">								<span class="col-md-6 col-sm-6 col-xs-6 bootstrap_default">台北市<br/>內湖區</span>								<span class="col-md-6 col-sm-6 col-xs-6 bootstrap_default">月薪<br/>200</span>							</li>							<li class="main_product_title">設計實習生（Design Intern）- 尋找桌遊愛好者</li>							<li class="main_product_company">								<ul>									<li class="col-md-12 col-sm-12 col-xs-12 bootstrap_default main_product_cdes">										<span>可樂旅遊_康福旅行社股份有限公司</span>										<p>資本額12億8000萬元											<br/>員工1000										</p>									</li>								</ul>							</li>							<li class="main_product_jdes">1.可獨立構思及完成設計，工作配合度高。 2.具備視覺美感及創意思維，且對基礎網頁有一定了解及經驗。3.商業平面設計為主1.可獨立構思及完成設計，工作配合度高。 2.具備視覺美感及創意思維...</li>							<li class="main_product_date">更新日期：2019-10-30</li>						</ul>						<div class="main_product_more">							<span class="col-md-6 col-sm-6 col-xs-6 bootstrap_default btn btn_more"><a href="/awshack/job/ptt/1" class="orange">詳細資訊</a></span>							<span class="col-md-6 col-sm-6 col-xs-6 bootstrap_default btn btn_apply"><a href="javascript:;" class="orange02" target="_blank">馬上應徵</a></span>						</div>					</div>					<div class="col-md-6 col-sm-6 col-xs-12 bootstrap_default main_product_list">						<ul>							<li class="main_product_key">								<span class="col-md-6 col-sm-6 col-xs-6 bootstrap_default">台北市<br/>內湖區</span>								<span class="col-md-6 col-sm-6 col-xs-6 bootstrap_default">月薪<br/>200</span>							</li>							<li class="main_product_title">設計實習生（Design Intern）- 尋找桌遊愛好者</li>							<li class="main_product_company">								<ul>									<li class="col-md-12 col-sm-12 col-xs-12 bootstrap_default main_product_cdes">										<span>可樂旅遊_康福旅行社股份有限公司</span>										<p>資本額12億8000萬元											<br/>員工1000										</p>									</li>								</ul>							</li>							<li class="main_product_jdes">1.可獨立構思及完成設計，工作配合度高。 2.具備視覺美感及創意思維，且對基礎網頁有一定了解及經驗。3.商業平面設計為主1.可獨立構思及完成設計，工作配合度高。 2.具備視覺美感及創意思維...</li>							<li class="main_product_date">更新日期：2019-10-30</li>						</ul>						<div class="main_product_more">							<span class="col-md-6 col-sm-6 col-xs-6 bootstrap_default btn btn_more"><a href="/awshack/job/ptt/1" class="orange">詳細資訊</a></span>							<span class="col-md-6 col-sm-6 col-xs-6 bootstrap_default btn btn_apply"><a href="javascript:;" class="orange02" target="_blank">馬上應徵</a></span>						</div>					</div>					<div class="col-md-6 col-sm-6 col-xs-12 bootstrap_default main_product_list">						<ul>							<li class="main_product_key">								<span class="col-md-6 col-sm-6 col-xs-6 bootstrap_default">台北市<br/>內湖區</span>								<span class="col-md-6 col-sm-6 col-xs-6 bootstrap_default">月薪<br/>200</span>							</li>							<li class="main_product_title">設計實習生（Design Intern）- 尋找桌遊愛好者</li>							<li class="main_product_company">								<ul>									<li class="col-md-12 col-sm-12 col-xs-12 bootstrap_default main_product_cdes">										<span>可樂旅遊_康福旅行社股份有限公司</span>										<p>資本額12億8000萬元											<br/>員工1000										</p>									</li>								</ul>							</li>							<li class="main_product_jdes">1.可獨立構思及完成設計，工作配合度高。 2.具備視覺美感及創意思維，且對基礎網頁有一定了解及經驗。3.商業平面設計為主1.可獨立構思及完成設計，工作配合度高。 2.具備視覺美感及創意思維...</li>							<li class="main_product_date">更新日期：2019-10-30</li>						</ul>						<div class="main_product_more">							<span class="col-md-6 col-sm-6 col-xs-6 bootstrap_default btn btn_more"><a href="/awshack/job/ptt/1" class="orange">詳細資訊</a></span>							<span class="col-md-6 col-sm-6 col-xs-6 bootstrap_default btn btn_apply"><a href="javascript:;" class="orange02" target="_blank">馬上應徵</a></span>						</div>					</div>				</div>				<!--pagenum-->				<div >					<ul class="pagenum">						<li class="btnleft">							<a href="#" title="回上頁">回上頁</a>						</li>						<li class="center">							<a class="on" title="第1頁">1</a>							<a href="#" title="第2頁">2</a>							<a href="#" title="第3頁">3</a>							<a href="#" title="第4頁">4</a>							<a href="#" title="第5頁">5</a>						</li>						<li class="btnright">							<a href="#" title="到下頁">到下頁</a>						</li>					</ul>				</div>				<!--END pagenum-->			</div>		</section>		<!--END 104職缺-->	</div>	<!--END WRAPPER-->@stop@section('customJs'){{--	<script src="{{ URL::asset('/js/3rd-party/jquery.history.js') }}" charset="utf-8"></script>--}}{{--	<script src="{{ URL::asset('/js/lists/job.js') }}" charset="utf-8"></script>--}}@stop