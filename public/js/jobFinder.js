var JOBFINDER = JOBFINDER || {};

JOBFINDER.namespace = function(ns_string) {
	var parts = ns_string.split('.'),
		parent = JOBFINDER,
		i;
	// strip redundant leading global
	if (parts[0] === "JOBFINDER") {
		parts = parts.slice(1);
	}
	for (i = 0; i < parts.length; i += 1) {
		// create a property if it doesn't exist
		if (typeof parent[parts[i]] === "undefined") {
			parent[parts[i]] = {};
		}
		parent = parent[parts[i]];
	}
	return parent;
};

var jobList = JOBFINDER.namespace("JOBFINDER.jobList");
var companyList = JOBFINDER.namespace("JOBFINDER.companyList");
var pagination = JOBFINDER.namespace("JOBFINDER.pagination");

/*
 * 取得工作資訊
 * @todo 預計重構為 取得工作資訊,公司資訊共用。
 */
JOBFINDER.listJobs = function(options, renderView) {
	var settings = $.extend({
		apiUrl: '/job/',
		page: 1,
		page_size: 50
	}, options || {});

	var sendData = {
		page: settings.page,
		page_size: settings.page_size
	};

	// console.log(sendData);

	$.ajax({
		url: settings.apiUrl,
		method: "GET",
		dataType: "JSON",
		data: sendData
	}).done(function(res) {
		// console.log(res);
		if (res.status === true) {
			$("#jobListBody").empty();

			var stateData = History.getState();
			if (!stateData.data) {
				History.pushState({
					state: page
				}, "Page" + page, "?page=" + page); // logs {state:1}, "State 1", "?state=1"
			}

			renderView(res);

			var pagerArgs = {
				currentPage: res.curr_page,
				minPage: 1,
				totalPage: res.total_page,
				rangeScope: 2
			};
			// console.log(pagerArgs);
			pagination.createPager(pagerArgs);
		} else {
			alert("取得資料失敗");
		}
	});
};

/*render 工作列表*/
jobList.renderView = function(data) {
	var source = $("#jobListTmpl").html();
	// Mustache.parse(template);
	var template = Handlebars.compile(source);
	var rendered = template(data);
	$("#jobListBody").append(rendered);
};

// companyList.listJobs = function(options, renderView) {
// 	var settings = $.extend({
// 		page: 1,
// 		page_size: 5
// 	}, options || {});
//
// 	$.ajax({
// 		url: "/company/",
// 		method: "GET",
// 		dataType: "JSON",
// 		data: settings
// 	}).done(function(res) {
// 		// console.log(res);
// 		if (res.status === true) {
// 			renderView(res);
// 		} else {
// 			alert("取得資料失敗");
// 		}
// 	});
// };

companyList.renderView = function(data) {
	var template = $("#jobListTmpl").html();
	Mustache.parse(template);
	var rendered = Mustache.render(template, data);
	$("#jobListBody").append(rendered);

	$('.job-detail').hide();

	// show/hide job detail
	$(".toggle-detail").each(function() {
		$(this).click(function() {
			$("tr[name='" + $(this).data('name') + "']").toggle();
		});
	});

	// $('.toggle-detail').each(function() {
	// 	var trBgColor = $(this).parent('td').css('background-color');
	// 	$(this).click(function() {
	// 		$('#' + $(this).data('name')).toggle();
	// 	});
	// });

	// job detail switch
	// $('.toggle-detail').each(function() {
	// 	$(this).click(function() {
	// 		$(this).parents('.job-detail').find('.detail-content').hide();
	// 		$(this).parents('.job-detail').find('.' + $(this).data('name')).show();
	// 	});
	// });
};

/*建立分頁元素*/
pagination.createPager = function(options) {
	// var settings = options || {};
	var settings = $.extend({
		pagination: []
	}, options || {});
	console.log('pager');
	console.log(settings);

	var pageRange = pagination.pageRangeCalculate(settings);

	for (var page = pageRange.start; page <= pageRange.end; page++) {
		settings.pagination.push(page);
	}

	console.log(settings);
	$('#pagerBody').empty();
	var source = $("#pagerTmpl").html();
	var template = Handlebars.compile(source);
	var rendered = template(settings);
	$('#pagerBody').append(rendered);

	/* bind 分頁標籤 event */
	$("#pagerUl a[name='goToPage']").click(function(e) {
		e.preventDefault();
		var page = $(this).attr("href");

		History.pushState({
			state: page
		}, "Page" + page, "?page=" + page); // logs {state:1}, "State 1", "?state=1"

		// JOBFINDER.jobList.listJobs(options, JOBFINDER.jobList.renderView);
	});

	$("#goToPrev").click(function(e) {
		e.preventDefault();
		var urlParams = getUrlParams();
		var page = urlParams.page;
		if (page - 1 <= 0) {
			page = 1;
		} else {
			page--;
		}

		History.pushState({
			state: page
		}, "Page" + page, "?page=" + page); // logs {state:1}, "State 1", "?state=1"

		// JOBFINDER.jobList.listJobs(options, JOBFINDER.jobList.renderView);
	});

	$("#goToNext").click(function(e) {
		e.preventDefault();
		var urlParams = getUrlParams();
		var page = urlParams.page;
		if (page >= settings.totalPage) {
			page = settings.totalPage;
		} else {
			page++;
		}

		History.pushState({
			state: page
		}, "Page" + page, "?page=" + page); // logs {state:1}, "State 1", "?state=1"

		// JOBFINDER.jobList.listJobs(options, JOBFINDER.jobList.renderView);
	});

};

/* 計算分頁範圍 */
pagination.pageRangeCalculate = function(options) {
	var settings = $.extend({
			currentPage: 1,
			minPage: 1,
			totalPage: 20,
			rangeScope: 2
		}, options || {}),
		startPage = settings.currentPage,
		endPage = settings.totalPage;

	if (settings.currentPage - settings.rangeScope <= 0) {
		endPage = settings.minPage + (settings.rangeScope * 2);
		startPage = settings.minPage;
	} else if ((settings.currentPage + settings.rangeScope) >= settings.totalPage) {
		startPage = settings.totalPage - (settings.rangeScope * 2);
	} else {
		startPage = settings.currentPage - settings.rangeScope;
		endPage = settings.currentPage + settings.rangeScope;
	}
	// console.log(startPage);
	return {
		start: startPage,
		end: endPage
	};
};

/*重新刷新工作列表內容*/
pagination.refreshPage = function() {
	var urlParams = getUrlParams();
	// console.log(urlParams);
	// var stateData = History.getState();
	var options = {
		"page": urlParams.page
	};
	JOBFINDER.listJobs(options, JOBFINDER.jobList.renderView);
	window.scrollTo(0, 0);
};

/* Handlebars helper 判斷分頁元素是否為當前頁面  顯示為highlight*/
Handlebars.registerHelper('isActive', function(context, options) {
	if (context === options.data.root.currentPage) {
		return options.fn(this);
	} else {
		return options.inverse(this);
	}
});

//監聽並觸發 popstate 動作
jobList.onstatechange = function() {
	window.onstatechange = function() {
		JOBFINDER.pagination.refreshPage();
	};
};
