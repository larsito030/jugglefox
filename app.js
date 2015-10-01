//pass argument to function "get validation feedback" (register or login)
//ajax validation for signup process
$(document).ready(function() {
        if(window.location.href.indexOf("active") > -1) {
            alert("your account has successfully been activated. You can log in now!");
      }
     $('#timer').html("00:00:0");
      get_random_tab_numbers();
      get_random_number();
      tab = $('#number_tabs').find('li');
      width = $('.numbers').width();
      $('.numbers').height(width+'px');
      out = $('.numbers').height();
      numbers = [];
      tabs_selected = [];
      login_status = null;
      count = 0;
      curr_tab = 0;
      operator = "";
      operator_count = 0;
      numbers_count = 0;
      completed = 0;
      adjacent = [-1,1,-3,-4,-5,3,4,5];
      adjacent_right = [-5,-4,-1,3,4];
      adjacent_left = [-4,-3,1,4,5];
      left_tabs = [0,4,8,12];
      right_tabs = [3,7,11,15];
      peripheral = []
      sec_digit_1 = 0;
      sec_digit_2 = 0;
      min_digit_1 = 0;
      min_digit_2 = 0;
      total_seconds = 0;
      status = "";
      state = "disabled";     
      valid = 0;
      login_button_disabled = true;
      success = 0;
      login_err_msg = "";
      hall_of_fame = "";
      success = 0;


function get_login_error_msg(login_err_msg) {
  if(('#login_error').html !== "") {
        $('#login_err').html(login_err_msg).css('font-size','3em').fadeOut(8000);
      }
}
      
function login_user() {
          var username = $('#username').val();
          var password = $('#password').val();
          $.post( "login.php", { username: username, password: password}, function(data) {  
                $('#e_msg').html(data);
                console.log('password: '+data);
                login_err_msg = data;
                login_status = data;
          }).done(function() {
              if(total_seconds > 0) {
                evaluate_score();
                location.reload();
              }
          });
}

function start_game() {
            stopWatch.call(this); 
            state = "enabled";
            $('#rows').load('highscore.php');
}

function skip(){
            initialize_new_step();
            count = 0;
            total_seconds = total_seconds+30;
      }

function undo() {
            status = "undo";
            reset_selected_tabs();
}

function store_selected_numbers(selected) {
        tabs_selected.push($('.numbers').index(selected));
        numbers.push(parseInt($(selected).find('span').html()));
        numbers_count++; 
        count++;
}

function run_game() {
    if(state === "enabled"){
      var last_tab = tabs_selected[tabs_selected.length-1];
          if(tabs_selected.length === 0 && ($(this).hasClass('numbers'))){
              $(this).addClass('selected');
              }   
            var index =  $(this).index();
            console.log("index: "+index);
            if(left_tabs.indexOf(last_tab) > -1) {
                var allowed = adjacent_left.map(get_allowed_fields);
            } else if(right_tabs.indexOf(last_tab) > -1) {
                var allowed = adjacent_right.map(get_allowed_fields);
            } else {
                var allowed = adjacent.map(get_allowed_fields);
            } 
            var selected = $(this);
            perform_operations(selected, allowed);
        }
}

function feedback_for_invalid_tab(allowed) {
    console.log("invalid");
    var message = $("<div id = \"invalid_tabs\">You must select an adjacent field!</div>");
    if(allowed.indexOf(curr_tab) === -1) {
      $(message).hide().appendTo('body').fadeIn(700).delay(1000).fadeOut(1000);
    }
      
     var valid = allowed.filter(function(index) {
                        return tabs_selected.indexOf(index) === -1
                  });
                  $.each((valid), function(index, value) {
                        var valid_fields =  $('.numbers').eq(value);
                          valid_fields.fadeIn(1000, function() {
                                valid_fields.addClass("green-border");
                          }).fadeOut(1000, function() {
                                valid_fields.removeClass("green-border").css('display','flex').parent().addClass('flex').css({'align-items': 'center', 'justify-content':'center'});
                          })
                  });
}

function perform_operations(selected,allowed) {
      if((count % 2 === 0) && selected.hasClass('numbers')){
                if(numbers_count === (operator_count)){
                    curr_tab = selected.index();
                    if(tabs_selected.indexOf(curr_tab) === -1){
                        if(numbers_count > 0){
                            console.log("is allowed: "+allowed.indexOf(curr_tab)); 
                            if(allowed.indexOf(curr_tab) !== -1){                   
                                store_selected_numbers(selected);                      
                              } else {     
                                  feedback_for_invalid_tab(allowed);
                              }
                      } else if(numbers_count === 0) {
                                store_selected_numbers(selected);
                              }
                    }    
                    do_Math();            
                }        
          } else if(count % 2 !== 0 && selected.hasClass('operator')){
            operator = selected.attr('id');
            count++;
            operator_count++;

          } else if(count % 2 === 0 && selected.hasClass('operator')){
            operator = selected.attr('id');

          }   
            feedback_for_invalid_tab();
}

function do_Math() {
  if(numbers.length === 2){
                      if(!$(this).hasClass('selected')){
                        style_selected_tabs();
                      }

                      if(operator === "plus"){
                        var result = numbers[0] + numbers[1];
                      } else if(operator === "minus"){
                        var result = numbers[0] - numbers[1];
                      } else if(operator === "multiply") {
                        var result = numbers[0] * numbers[1];
                      } else if(operator === "divide") {
                        var result = Math.round(numbers[0] / numbers[1]);
                      }
                      
                      numbers = [];
                      numbers.push(result);
                      $('#calc').html(numbers);
                      check_if_solved(); 
                    }             
}


function post_highscore_to_database(){
      $.post( "set_highscore.php", { score: total_seconds} );
}


function render_checkmark_green(){
        $('li.glyphicon:eq('+(completed-1)+')').css('color','rgb(125,198,145)');
      }

      function get_random_number(){
        var random = Math.floor((Math.random() * 100) + 1);
        $('#random_number').html(random);
      }

      function get_random_tab_numbers(){
        for(var i=0;i<16;i++){
          var random = Math.ceil(Math.random() * 9);
          $('li.numbers:eq('+i+')').find('span').html(random);
        }
      }

      function reset_selected_tabs(){
          $('li').removeClass('selected');
          $('.line').remove();
          numbers = [];
          tabs_selected = [];
          count = (status === "next_step") ? 0 : 0;
          curr_tab = 0;
          operator = "";
          operator_count = 0;
          numbers_count = 0;
          $('#calc').html("");
      }

      function initialize_new_step(){
        status = "next_step";
        reset_selected_tabs();
        render_checkmark_green();
        get_random_number();
        get_random_tab_numbers();  
        $('#success')[0].play();
      }

      function get_allowed_fields(el){
            var pos = curr_tab + el;
            return pos;
      }

      function get_overlay(){
          $('body').append("<div id=\"overlay\"></div>");
                var docHeight = $(document).height();
                $("#overlay")
                  .height(docHeight)
                  .css({
                    'opacity' : 0.4,
                    'position': 'absolute',
                    'top': 0,
                    'left': 0,
                    'background-color': 'black',
                    'width': '100%',
                    'z-index': 5000
                });
      }

      function get_modal(){
        $('#overlay').append("<div id=\"modal\">Test</div>");
          $('#modal').css({
                        'top':'20%',
                        'left':'30%',
                        'margin': 'auto',
                        'opacity' : 1,
                        'position': 'absolute',
                        'background': 'white',
                        'border-radius': '10px',
                        'width': '40%',
                        'height': '100px',
                        'z-index': 6000
                   })
      }

      function evaluate_score() {
        $.post( "evaluate_score.php", { new_score: total_seconds })
                    .done(function( data ) {
                      var message = data;
                      var message_box = '<span id="feedback">'+message+'</span>';
                      $('#overlay').fadeOut(5000);
                      $(message_box).appendTo('body').fadeOut(5000);
                      location.reload();
                  });
      }

      function feedback_upon_completion() {
          if($('#log_in').html() === "login"){
                  $('#modal').css('display','block').find('h2').html('Wanna log in?');
                } else{
                  evaluate_score();
                }
            
      }

      function play_completion_sound() {
          $('#completed')[0].play();
      }

      function check_if_solved(){
        if(numbers_count > 2 && ((numbers[0]) === parseInt($("#random_number").html()))){
            completed++;
            if(completed === 5){
              render_checkmark_green();
              post_highscore_to_database();
              get_overlay();
              play_completion_sound();
              setTimeout(feedback_upon_completion, 1500);
            } else {
              initialize_new_step();
            }  
        }
      }

      function stopWatch(){
      	$('#timer').html("");
          setInterval(function(){ 
              if(completed < 5){
              	 total_seconds++;
              }  
              get_time_format();    
              var time_elapsed = get_time_format();
              $('#timer').html(time_elapsed);
             }, 100);
      }

      function get_time_format(){
          if(total_seconds <10){
              time_elapsed = "00:00:"+total_seconds;
            } else if(total_seconds > 9 && total_seconds < 100) {
                var s1 = Math.floor(total_seconds/10);
                var s2 = total_seconds % 10;
                time_elapsed = "00:0"+s1+":"+s2;
            } else if(total_seconds > 99 && total_seconds < 600){
                var s1 = Math.floor((total_seconds % 100)/10);
                var s2 = (total_seconds % 100) % 10;
                var m2 = Math.floor(total_seconds/100);
                time_elapsed = "00:"+m2+s1+":"+s2;
            } else if(total_seconds > 599 & total_seconds < 6000){
                var m1 = Math.floor(total_seconds / 600);
                var m2 = Math.floor((total_seconds % 600) / 100);
                var s1 = Math.floor(((total_seconds % 600) % 100) / 10);
                var s2 = ((total_seconds % 600) % 100) % 10;
                time_elapsed = "0"+m1+":"+m2+s1+":"+s2;
            }
            return time_elapsed;
      }

function style_selected_tabs(){
	var first_tab = tabs_selected[tabs_selected.length-2];
    var second_tab = tabs_selected[tabs_selected.length-1];
                        var first_tab_html = $('.numbers:eq('+first_tab+')');
                        var second_tab_html = $('.numbers:eq('+second_tab+')');                       
                        $(first_tab_html).addClass('selected tab_link');
                        $(second_tab_html).addClass('selected');
                        var pos1 = $(first_tab_html).offset();
                        var pos2 = $(second_tab_html).offset();
                        var tile_width = $('.numbers').css('width');
                        var tile_height = $('.numbers').css('height');
                        var x1 = pos1.left + (parseInt(tile_width)/2);
                        //console.log("x1: "+x1);
                        //console.log("half tile width: "+parseInt(tile_width)/2);
                        var x2 = pos2.left + (parseInt(tile_width)/2);
                        var y1 = pos1.top + (parseInt(tile_height)/2);
                        var y2 = pos2.top + (parseInt(tile_height)/2);
                            var length = Math.sqrt((x1-x2)*(x1-x2) + (y1-y2)*(y1-y2));
                            var angle  = Math.atan2(y2 - y1, x2 - x1) * 180 / Math.PI;
                            var transform = 'rotate('+angle+'deg)';
                            var line = $('<div>')
                            .appendTo('body')
                            .addClass('line')
                            .css({
                              'position': 'absolute',
                              'transform': transform
                            })
                            .width(length)
                            .offset({left: x1, top: y1});
                      
}

function enable_login_button() {
  //console.log("login_button_disabled: "+login_button_disabled);
  if(login_button_disabled === true) {
    $('#login_submit').prop('disabled', true);
  } else if(login_button_disabled ===false) {
    $('#login_submit').prop('disabled', false);
  }
}

function get_feedback() {
    $.post( "evaluate_score.php", function( data ) {
      alert(data);
    });
  }

function mark_login_data_as_invalid(inputfield) {
    var error_messages = [    "This username is already taken!",
                              "Your username needs to contain at least 6 characters!",
                              "Your password needs to have at least 6 characters!",
                              "Your passwords are not matching!",
                              "This is not a valid email address!",
                              "This email address is already registered!"
    ]
    
    var error_msg = error_messages[success-1];
    login_button_disabled = true;
    var valid = $('.username_valid');
    $("#"+inputfield).css('border', '3px solid red');
          if(!($('#'+inputfield).next().hasClass('error_message')) && (inputfield.indexOf('signup') > -1 || inputfield ==="password2")) {
            var inserted_line = "<li class=\"col-xs-12 error_message\">"+error_msg+"</li>";
            $("#"+inputfield).after(inserted_line);
            $(inserted_line).html(error_msg);
          }
      if($(valid).length > 0 &&($(valid).parent().is('#valid_'+inputfield) || $(valid).parent().is('.signup_fields'))) {
        $('#valid_'+inputfield).find(valid).remove();
        $("#"+inputfield).siblings('.username_valid').remove();
            }
    }
//if success=2 then render second input field green
function mark_login_data_as_valid(username, password, checkmark, inputfield, email, password2, checkmark) {
    console.log('mark_login_data_as_valid: inputfield: ' + inputfield + 'success: ' + success);
    if((inputfield === 'password' && success === 2) || inputfield === 'username') {
      $("#"+inputfield).css('border', '3px solid rgb(125,198,145)');
    }
    var checkmark = "<span class=\"username_valid glyphicon glyphicon-ok small\">";

      if((inputfield.indexOf("signup") > -1 || inputfield === "password2") && !($('#'+inputfield).next().hasClass('glyphicon'))) {
          $('#'+inputfield).after(checkmark);
          $("#"+inputfield).parent().find('.error_message').remove();
      } else {
          if($('#valid_'+inputfield).find($(".username_valid")).length < 1) {
            if((inputfield === 'password' && success === 2) || inputfield === 'username') {
                $(checkmark).appendTo('#valid_'+inputfield);
            }   
          }
          if(username.length > 0 && password.length > 0) {
              login_button_disabled = false;
            }            
      }
      console.log("input field: " + inputfield);
      }

function get_validation_feedback() {
            var username = $('#username').val();
            var password = $('#password').val();
        inputfield = $(this).attr('id');
        $.post('validation_feedback.php', { username: username, password: password}, function(data) {     
          success = parseInt(data);
          console.log("data: "+success);
        }).done(function(username, password) {
          var checkmark = "<span class=\"username_valid glyphicon glyphicon-ok small\">";
         // console.log("success: " + success);
          if(success === 1 || success === 2) {
              console.log('success:' + success);
              if(success === 1 && inputfield === 'password' ) {
                  mark_login_data_as_invalid(inputfield);
              }
                  mark_login_data_as_valid(username, password, checkmark, inputfield);
          } else {
              mark_login_data_as_invalid(inputfield);
          }
          enable_login_button();
        });         
}

function validate_signup_form() {
        var username = $('#signup_username').val();
        var password = $('#signup_password').val();
        var password2 = $('#password2').val();
        var email = $('#signup_email').val();
        var inputfield = $(this).attr('id');
        $.post('validate_signup_data.php', { username: username, password: password, password2: password2, email: email, inputfield: inputfield}, function(data) {     
          success = parseInt(data);
          console.log("error_type: "+data);
        }).done(function() {
          var checkmark = "<span class=\"username_valid glyphicon glyphicon-ok small\">";
          if(success == 0) {
              mark_login_data_as_valid(username, password, checkmark, inputfield, email, password2);
          } else {
              mark_login_data_as_invalid(inputfield,success);
          }
          enable_login_button();
        })

}

function show_login_form(e, selected) {
          var status = $(selected).html();
          if($('#log_in').html() === "login") {
            get_overlay();
            $('#modal').css('display', 'block');
            e.preventDefault();
          } 
}

function close_modal() {
    $('#modal').css('display','none');
    $('#overlay').remove();
}

function get_hall_of_fame_html(e,selected) {
    e.preventDefault();
    var file = "templates/" + $(selected).attr('id') + ".php";
    $.post(file, function(data) {
      hall_of_fame = data;
        $('#righthand_panel').html(data);
    hall_of_fame = data;
    });
}

      $('#skip').on('click', skip);     
      $('#undo').on('click', undo);
      $("#start").on("click", start_game);
      $('#log_in').on('click', function(e) {
          var selected = $(this);
          show_login_form(e,selected);
        });

      $('#login_submit').on('click', login_user); 
      $('li').on('click', run_game);
      $('#username, #password').on('keyup', get_validation_feedback);
      $('#signup_fields').find('input').on('keyup', validate_signup_form);
      $('.glyphicon-remove').on('click', close_modal);
      $('#hall_of_fame').on('click', function(e) {
          var selected = $(this);
          get_hall_of_fame_html(e,selected);
      });
      $('#righthand_panel').on('click', '.back', function() {
            $('#righthand_panel').load('templates/righthand_panel.php');
            $.when($.ajax('templates/righthand_panel.php')).done(function() {
              $('#timer').html("00:00:0");
              get_random_number();
            });
      });
      $('#help').on('click', function() {
          $('#righthand_panel').load('templates/help.html');
          get_random_number();
      })


}); 



  



