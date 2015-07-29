@extends('lists/main')

@section('content')
<section class="content">
	<table class="jobtable">
		<thead>
			<tr>
				<th>職缺列表</th>
				<th>刊登日期</th>
				<th>公司名稱<button class="table-sort"><i class="fa fa-chevron-down"></i></button></th>
				<th>公司類型</th>
				<th>所在地</th>
				<th>總人數</th>
				<th>經歷需求</th>
				<th>職缺數</th>
				<th>資本額</th>
			</tr>
		</thead>
	<tbody id="jobListBody">
	</tbody>
	</table>
</section>