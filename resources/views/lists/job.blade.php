@extends('lists/main')@section('content')<section class="content">	<table class="jobtable">		<thead>			<tr>				<!-- <th><input type="checkbox" class="checkbox-orders" name="checkbox-orders"></th> -->				<!-- <th>職缺列表</th> -->				<th>刊登日期</th>				<th>職稱</th>				<th>公司名稱<button id="btnSortCompany" class="table-sort"><i class="fa fa-chevron-up"></i></button></th>				<th>新資範圍<button id="btnSortSalary" class="table-sort"><i class="fa fa-chevron-up"></i></button></th>				<th>所在地</th>				<th>總人數</th>				<th>年資</th>				<th>職缺數</th>				<th>資本額</th>			</tr>		</thead>	<tbody id="jobListBody">	</tbody>	</table>	<div id="pagerBody" class="pagenavi">	</div></section><script id="jobListTmpl" type="text/x-handlebars-template">		@{{#each rows}}		<tr>			<td>@{{appear_date}}</td>			<td><div class="fixword">@{{title}}</td>			<td><div class="fixword">@{{name}}</div></td>			<td>@{{pay}}</td>			<td>@{{job_addr_no_descript}}</td>			<td>@{{employees}}</td>			<td>@{{period}}</td>			<td>@{{job_count}}</td>			<td>@{{capital}}</td>		</tr>		@{{/each}}</script>		<!--<li class="active"><a href="#" >1</a></li>--><!-- 作用中 --><script id="pagerTmpl" type="text/x-handlebars-template">	<ul id="pagerUl">		<li class="prev"><a id="goToPrev" href=""><i class="fa fa-chevron-left"></i></a></li>		@{{#each pagination}}			@{{#isActive this}}				<li  class="active"><a name="goToPage" href="@{{this}}" >@{{this}}</a></li><!-- 作用中 -->			@{{else}}				<li><a name="goToPage" href="@{{this}}" >@{{this}}</a></li>			@{{/isActive}}		@{{/each}}		<li class="next"><a id="goToNext" href=""><i class="fa fa-chevron-right"></a></i></li>	</ul></script>@stop@section('customJs')	<script src="{{ URL::asset('/js/3rd-party/jquery.history.js') }}" charset="utf-8"></script>	<script src="{{ URL::asset('/js/lists/job.js') }}" charset="utf-8"></script>@stop