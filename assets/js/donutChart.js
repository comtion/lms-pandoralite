
google.load("visualization", "1", {packages:["corechart"]});

google.setOnLoadCallback(drawTotal);
google.setOnLoadCallback(drawEnrolled);
google.setOnLoadCallback(drawInprogress);
google.setOnLoadCallback(drawNotstart);
// For work group
google.setOnLoadCallback(drawWorkGroup1);
google.setOnLoadCallback(drawWorkGroup2);
google.setOnLoadCallback(drawWorkGroup3);
google.setOnLoadCallback(drawWorkGroup4);
google.setOnLoadCallback(drawWorkGroup5);
google.setOnLoadCallback(drawWorkGroup6);
drawTotal();
function drawTotal() {
	var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['',     coe],
          ['',     parseInt(countcoe)-parseInt(coe)]
        ]);
	console.log(data);
	var options = {
		title: "",
		pieHole: 0.75,
		pieSliceBorderColor: "none",
     colors: ['#1f2b5b', '#e6e6e6' ],
		legend: {
			position: "none"
		},
		pieSliceText: "none",
		tooltip: {
			trigger: "none"
		}
	};

	var chart = new google.visualization
			.PieChart(document.getElementById('totalChart'));

	chart.draw(data, options);

}

function drawEnrolled() {
	var data = google.visualization.arrayToDataTable([
		['Content', 'Size'],
		['Course',     coc],
		['Total',      coc == 0 ? 1 : coe-coc]
	]);

	var options = {
		title: "",
		pieHole: 0.75,
		pieSliceBorderColor: "none",
     colors: ['#00aa90', '#e6e6e6' ],
		legend: {
			position: "none"
		},
		pieSliceText: "none",
		tooltip: {
			trigger: "none"
		}
	};

	var chart = new google.visualization
			.PieChart(document.getElementById('enrolledChart'));

	chart.draw(data, options);

}

function drawInprogress() {
	var data = google.visualization.arrayToDataTable([
		['Content', 'Size'],
		['Course',     coip],
		['Total',      coip == 0 ? 1 : coe-coip]
	]);

	var options = {
		title: "",
		pieHole: 0.75,
		pieSliceBorderColor: "none",
     colors: ['#eae043', '#e6e6e6' ],
		legend: {
			position: "none"
		},
		pieSliceText: "none",
		tooltip: {
			trigger: "none"
		}
	};

	var chart = new google.visualization
			.PieChart(document.getElementById('inprogressChart'));

	chart.draw(data, options);

}

function drawNotstart() {
	var data = google.visualization.arrayToDataTable([
		['Content', 'Size'],
		['Course',     cons],
		['Total',      cons == 0 ? 1 : coe-cons]
	]);

	var options = {
		title: "",
		pieHole: 0.75,
		pieSliceBorderColor: "none",
     colors: ['#e24242', '#e6e6e6' ],
		legend: {
			position: "none"
		},
		pieSliceText: "none",
		tooltip: {
			trigger: "none"
		}
	};

	var chart = new google.visualization
			.PieChart(document.getElementById('notstartChart'));

	chart.draw(data, options);

}

function drawWorkGroup1() {
	var data = google.visualization.arrayToDataTable([
		['Content', 'Size'],
		['Course',     wk1],
		['Total',      wk1 == 0 ? 1 : 0]
	]);

	var options = {
		title: "",
		pieHole: 0.75,
		pieSliceBorderColor: "none",
     colors: ['#1f2b5b', '#e6e6e6' ],
		legend: {
			position: "none"
		},
		pieSliceText: "none",
		tooltip: {
			trigger: "none"
		}
	};

	var chart = new google.visualization
			.PieChart(document.getElementById('workGroup1Chart'));

	chart.draw(data, options);

}

function drawWorkGroup2() {
	var data = google.visualization.arrayToDataTable([
		['Content', 'Size'],
		['Course',     wk2],
		['Total',      wk2 == 0 ? 1 : 0]
	]);

	var options = {
		title: "",
		pieHole: 0.75,
		pieSliceBorderColor: "none",
     colors: ['#1f2b5b', '#e6e6e6' ],
		legend: {
			position: "none"
		},
		pieSliceText: "none",
		tooltip: {
			trigger: "none"
		}
	};

	var chart = new google.visualization
			.PieChart(document.getElementById('workGroup2Chart'));

	chart.draw(data, options);

}

function drawWorkGroup3() {
	var data = google.visualization.arrayToDataTable([
		['Content', 'Size'],
		['Course',     wk3],
		['Total',      wk3 == 0 ? 1 : 0]
	]);

	var options = {
		title: "",
		pieHole: 0.75,
		pieSliceBorderColor: "none",
     colors: ['#1f2b5b', '#e6e6e6' ],
		legend: {
			position: "none"
		},
		pieSliceText: "none",
		tooltip: {
			trigger: "none"
		}
	};

	var chart = new google.visualization
			.PieChart(document.getElementById('workGroup3Chart'));

	chart.draw(data, options);

}
function drawWorkGroup4() {
	var data = google.visualization.arrayToDataTable([
		['Content', 'Size'],
		['Course',     wk4],
		['Total',      wk4 == 0 ? 1 : 0]
	]);

	var options = {
		title: "",
		pieHole: 0.75,
		pieSliceBorderColor: "none",
     colors: ['#1f2b5b', '#e6e6e6' ],
		legend: {
			position: "none"
		},
		pieSliceText: "none",
		tooltip: {
			trigger: "none"
		}
	};

	var chart = new google.visualization
			.PieChart(document.getElementById('workGroup4Chart'));

	chart.draw(data, options);

}

function drawWorkGroup5() {
	var data = google.visualization.arrayToDataTable([
		['Content', 'Size'],
		['Course',     wk5],
		['Total',      wk5 == 0 ? 1 : 0]
	]);

	var options = {
		title: "",
		pieHole: 0.75,
		pieSliceBorderColor: "none",
     colors: ['#1f2b5b', '#e6e6e6' ],
		legend: {
			position: "none"
		},
		pieSliceText: "none",
		tooltip: {
			trigger: "none"
		}
	};

	var chart = new google.visualization
			.PieChart(document.getElementById('workGroup5Chart'));

	chart.draw(data, options);

}

function drawWorkGroup6() {
	var data = google.visualization.arrayToDataTable([
		['Content', 'Size'],
		['Course',     wk6],
		['Total',      wk6 == 0 ? 1 : 0]
	]);

	var options = {
		title: "",
		pieHole: 0.75,
		pieSliceBorderColor: "none",
     colors: ['#1f2b5b', '#e6e6e6' ],
		legend: {
			position: "none"
		},
		pieSliceText: "none",
		tooltip: {
			trigger: "none"
		}
	};

	var chart = new google.visualization
			.PieChart(document.getElementById('workGroup6Chart'));

	chart.draw(data, options);

}
