// COUNT ADD
var count_no_1 = 2;
var count_no_2 = 2;
var count_no_3 = 2;
var count_no_4 = 2;
var count_no_5 = 2;
var count_no_6 = 2;
var count_no_7 = 2;
var count_no_8 = 2;
var count_no_9 = 2;
var count_no_10 = 2;
var count_element = 2;
var count_cate = 2;

function onClick_1() {
	count_no_1 += 1;
}

function onClick_2() {
	count_no_2 += 1;
}

function onClick_3() {
	count_no_3 += 1;
}

function onClick_4() {
	count_no_4 += 1;
}

function onClick_5() {
	count_no_5 += 1;
}

function onClick_6() {
	count_no_6 += 1;
}

function onClick_7() {
	count_no_7 += 1;
}

function onClick_8() {
	count_no_8 += 1;
}

function onClick_9() {
	count_no_9 += 1;
}

function onClick_10() {
	count_no_10 += 1;
}

// Count for category
function countCate() {
	count_cate += 1;

	if(count_cate >= 11){
		$("#addCategory").remove();
	}
}