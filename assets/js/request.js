 $(document).ready(function() {
  $('.info-content td .fa').hide();
  
  $('#self-staff').on('mouseenter', '.self-staff', function() {
    $(this).children('td:first').children('.fa.fa-plus-circle').show('fast');
    $(this).children('td:first').children('.fa.fa-minus-circle').show('fast');
  });
  $('#self-staff').on('mouseleave', '.self-staff', function() {
    $('.info-content td .fa').hide('fast');
  });
  $('#self-staff').on('click', '.self-staff .fa-plus-circle', function() {
    var index = $(this).parent().parent().index();
    addStaff('self', index);
  });
  $('#self-staff').on('click', '.self-staff .fa-minus-circle', function() {
    var index = $(this).parent().parent().index();
    removeStaff('self', index);
  });
  $('#other-staff').on('mouseenter', '.other-staff', function() {
    $(this).children('td:first').children('.fa.fa-plus-circle').show('fast');
    $(this).children('td:first').children('.fa.fa-minus-circle').show('fast');
  });
  $('#other-staff').on('mouseleave', '.other-staff', function() {
    $('.info-content td .fa').hide('fast');
  });
  $('#other-staff').on('click', '.other-staff .fa-plus-circle', function() {
    var index = $(this).parent().parent().index();
    addStaff('other', index);
  });
  $('#other-staff').on('click', '.other-staff .fa-minus-circle', function() {
    var index = $(this).parent().parent().index();
    removeStaff('other', index);
  });
  
  $('#self-print').click(function() {
    window.print();
  });
  $('#other-print').click(function() {
    window.print();
  });
});

function sortNum(tab, len) {
  var i;
  for(i=0; i<len; i++) {
    $('#' + tab + '-staff tr:eq(' + (i+3) + ')').children('td:first').children('.no-staff').text((i+1) + '.');
  }
}

function addStaff(tab, index) {
  var len = $('tr.' + tab + '-staff').length;
  if(len == 7) {
    alert('Can not add more than 7 staves!');
  } else {
    var html = '<tr class="' + tab + '-staff">' +
                '<td align="right">' +
                  '<i class="fa fa-plus-circle" aria-hidden="true"></i>' +
                  '<i class="fa fa-minus-circle" aria-hidden="true"></i>' +
                '<span class="no-staff"><span></td>' +
                '<td><input type="text"></td>' +
                '<td><input type="text"></td>' +
                '<td><input type="text"></td>' +
                '<td><input type="text"></td>' +
              '</tr>';
    $('#' + tab + '-staff tr:eq(' + index + ')').after(html);
    sortNum(tab, len+1);
  }
}

function removeStaff(tab, index) {
  var len = $('tr.' + tab + '-staff').length;
  if(len == 1) {
    alert('Must have at least one staff!');
  } else {
    $('#' + tab + '-staff tr:eq(' + index + ')').remove();
    sortNum(tab, len-1);
  }
}