 var index = 0;
var img_src_array = [
                      img_src + emp_c + '.png',
                      img_src + emp_c + '.jpg',
                      img_src + 'empty_profile.png'
                    ];

$(document).ready(function() {
      $('#profile-img').prop('src', img_src_array[index]);

      $('#profile-img').error(function() {
        $('#profile-img').prop('src', img_src_array[++index]);
      });
  });
