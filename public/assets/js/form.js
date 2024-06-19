/*---------------------Form----------------------*/
$(document).ready(function () {

    console.log("screen", screen.height);
    console.log("body", $('body').height());
    console.log("window", $(window).height());
    console.log("document", $(document).height());

    var section1 = $("#contact_section_q1");
    var section2 = $("#contact_section_q2");
    var section3 = $("#contact_section_q3");
    var section4 = $("#contact_section_q4");
    var section5 = $("#contact_section_q5");
    var section6 = $("#contact_section_q6");

    var inputq1 = $("#form-q1");
    var inputq2 = $("#form-q2");
    var inputq3 = $("#form-q3");
    var inputq4 = $("#form-q4");
    var inputq5 = $("#form-q5");
    var inputq6 = $("#form-q6");

    var border_color = "#253992";

    var current_page = "1";

    $(".button_ok").click(function() {
        $(".contact_section").css("display", "block");
    });

    //section 1

    function form_scroll_q1() {

        console.log(inputq1.val());
        console.log("test");

        if (inputq1.val() != "" && inputq1.val() != null && inputq1.val() != "null" && inputq1.val() != undefined) {

            section1.css("transform", "translateY(-" + $("#contact_section_q1").height() + "px)");
            section2.css("transform", "translateY(-" + $("#contact_section_q1").height() + "px)");
            section3.css("transform", "translateY(-" + $("#contact_section_q1").height() + "px)");
            section4.css("transform", "translateY(-" + $("#contact_section_q1").height() + "px)");
            section5.css("transform", "translateY(-" + $("#contact_section_q1").height() + "px)");
            section6.css("transform", "translateY(-" + $("#contact_section_q1").height() + "px)");

            $("#contact-form").css("height", $("#contact_section_q2").height());

            current_page = "2";

            inputq1.css("border-bottom", "2px solid " + border_color);

            inputq1.trigger('blur');

            inputq1.attr("value", inputq1.val());

        }

        if (inputq1.val() == "" || inputq1.val() == null || inputq1.val() == undefined || inputq1.val() != "null") {

            inputq1.css("border-bottom", "2px solid red");
            //			$("#form-name").addClass("form-name-error");

        }

    }

    $(document).on("keypress", function (e) {
        var keyCode = (e.keyCode ? e.keyCode : e.which);
        if (keyCode === 9) {
            console.log("tab clicked")

            e.preventDefault();

        }
    });

    //    document.onkeydown = PresTab;
    document.onkeyup = PresTab;

    function PresTab(e) {
        var keycode = (window.event) ? event.keyCode : e.keyCode;
        if (keycode == 9)
            console.log("tab clicked")
        e.preventDefault();
        event.preventDefault();
        //           alert('tab key pressed');
    }

    inputq1.on("keypress", function (e) {
        var keyCode = (e.keyCode ? e.keyCode : e.which);
        if (keyCode == 13) {
            e.preventDefault();
            form_scroll_q1();

        }
    });

    $("#button_ok1").click(function () {

        form_scroll_q1();
        console.log("test");

    });


    //section 2

    function form_scroll_q2() {

        console.log("function start");

        if (inputq2.val() != "" && inputq2.val() != null && inputq2.val() != "null" && inputq2.val() != undefined) {

            console.log("function not empty");

            //			$('html, body').animate({
            //				scrollTop: $(window).height() * 2
            //			}, 500);

            section1.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height())) + "px)");
            section2.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height())) + "px)");
            section3.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height())) + "px)");
            section4.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height())) + "px)");
            section5.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height())) + "px)");
            section6.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height())) + "px)");

            $("#contact-form").css("height", $("#contact_section_q3").height());

            current_page = "3";

            inputq2.css("border-bottom", "2px solid " + border_color);

            inputq2.trigger('blur');

            inputq2.attr("value", inputq2.val());

        } else {

            //		if ($("#form-email").val() == "") {

            inputq2.css("border-bottom", "2px solid red");
            //			inputq1.addClass("form-name-error");

        }

    }

    inputq2.on("keypress", function (e) {
        var keyCode = (e.keyCode ? e.keyCode : e.which);
        if (keyCode == 13) {

            e.preventDefault();
            form_scroll_q2();

        }
    });

    $("#button_ok2").click(function () {

        console.log("enter click email");
        form_scroll_q2();

    });



    //section 3

    function form_scroll_q3() {

        //		console.log("function start");

        //		console.log(inputq3.val().length);

        if (inputq3.val() != "" && inputq3.val() != null && inputq3.val() != "null" && inputq3.val() != undefined) {

            //			$('html, body').animate({
            //				scrollTop: $(window).height() * 3
            //			}, 500);

            section1.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height())) + "px)");
            section2.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height())) + "px)");
            section3.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height())) + "px)");
            section4.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height())) + "px)");
            section5.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height())) + "px)");
            section6.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height())) + "px)");

            $("#contact-form").css("height", $("#contact_section_q4").height());

            current_page = "4";

            inputq3.css("border-bottom", "2px solid " + border_color);

            inputq3.trigger('blur');

            inputq3.attr("value", inputq3.val());

        } else {

            //		if ($("#form-tel").val() == "") {

            inputq3.css("border-bottom", "2px solid red");
            //			$("#form-name").addClass("form-name-error");

        }

    }

    inputq3.on("keypress", function (e) {
        var keyCode = (e.keyCode ? e.keyCode : e.which);
        if (keyCode == 13) {

            e.preventDefault();
            form_scroll_q3();

        }
    });

    $("#button_ok3").click(function () {

        console.log("enter click email");
        form_scroll_q3();

    });




    //section 4

    function form_scroll_q4() {

        //		console.log("function start");

        //		console.log(inputq3.val().length);

        if (inputq4.val() != "" && inputq4.val() != null && inputq4.val() != "null" && inputq4.val() != undefined) {

            //			$('html, body').animate({
            //				scrollTop: $(window).height() * 3
            //			}, 500);

            section1.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height()) + parseInt($("#contact_section_q4").height())) + "px)");
            section2.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height()) + parseInt($("#contact_section_q4").height())) + "px)");
            section3.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height()) + parseInt($("#contact_section_q4").height())) + "px)");
            section4.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height()) + parseInt($("#contact_section_q4").height())) + "px)");
            section5.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height()) + parseInt($("#contact_section_q4").height())) + "px)");
            section6.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height()) + parseInt($("#contact_section_q4").height())) + "px)");

            $("#contact-form").css("height", $("#contact_section_q5").height());

            current_page = "5";

            inputq4.css("border-bottom", "2px solid " + border_color);

            inputq4.trigger('blur');

            inputq4.attr("value", inputq4.val());

        } else {

            //		if ($("#form-tel").val() == "") {

            inputq4.css("border-bottom", "2px solid red");
            //			$("#form-name").addClass("form-name-error");

        }

    }

    inputq4.on("keypress", function (e) {
        var keyCode = (e.keyCode ? e.keyCode : e.which);
        if (keyCode == 13) {

            e.preventDefault();
            form_scroll_q4();

        }
    });

    $("#button_ok4").click(function () {

        console.log("enter click email");
        form_scroll_q4();

    });

    
    
    function form_scroll_q5() {

        //		console.log("function start");

        //		console.log(inputq3.val().length);

        if (inputq5.val() != "" && inputq5.val() != null && inputq5.val() != "null" && inputq5.val() != undefined) {

            //			$('html, body').animate({
            //				scrollTop: $(window).height() * 3
            //			}, 500);

            section1.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height()) + parseInt($("#contact_section_q4").height()) + parseInt($("#contact_section_q5").height())) + "px)");
            
            section2.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height()) + parseInt($("#contact_section_q4").height()) + parseInt($("#contact_section_q5").height())) + "px)");
            
            section3.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height()) + parseInt($("#contact_section_q4").height()) + parseInt($("#contact_section_q5").height())) + "px)");
            
            section4.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height()) + parseInt($("#contact_section_q4").height()) + parseInt($("#contact_section_q5").height())) + "px)");
            
            section5.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height()) + parseInt($("#contact_section_q4").height()) + parseInt($("#contact_section_q5").height())) + "px)");
            
            section6.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height()) + parseInt($("#contact_section_q4").height()) + parseInt($("#contact_section_q5").height())) + "px)");
            


            $("#contact-form").css("height", $("#contact_section_q6").height());

            current_page = "6";

            inputq5.css("border-bottom", "2px solid " + border_color);

            inputq5.trigger('blur');

            inputq5.attr("value", inputq5.val());

        } else {

            //		if ($("#form-tel").val() == "") {

            inputq5.css("border-bottom", "2px solid red");
            //			$("#form-name").addClass("form-name-error");

        }

    }

    inputq5.on("keypress", function (e) {
        var keyCode = (e.keyCode ? e.keyCode : e.which);
        if (keyCode == 13) {

            e.preventDefault();
            form_scroll_q5();

        }
    });

    $("#button_ok5").click(function () {

        console.log("enter click email");
        form_scroll_q5();

    });



    //back button 2

    $(".form_arrow_back2").click(function () {

        //		$('html, body').animate({
        //			scrollTop: 0
        //		}, 500);

        console.log("test");

        section1.css("transform", "translateY(0px)");
        section2.css("transform", "translateY(0px)");
        section3.css("transform", "translateY(0px)");
        section4.css("transform", "translateY(0px)");
        section5.css("transform", "translateY(0px)");
        section6.css("transform", "translateY(0px)");

        $("#contact-form").css("height", $("#contact_section_q1").height());

        current_page = "1";

    });

    //back button 3

    $(".form_arrow_back3").click(function () {

        //		$('html, body').animate({
        //			scrollTop: $(window).height()
        //		}, 500);

        section1.css("transform", "translateY(-" + $("#contact_section_q1").height() + "px)");
        section2.css("transform", "translateY(-" + $("#contact_section_q1").height() + "px)");
        section3.css("transform", "translateY(-" + $("#contact_section_q1").height() + "px)");
        section4.css("transform", "translateY(-" + $("#contact_section_q1").height() + "px)");
        section5.css("transform", "translateY(-" + $("#contact_section_q1").height() + "px)");
        section6.css("transform", "translateY(-" + $("#contact_section_q1").height() + "px)");

        $("#contact-form").css("height", $("#contact_section_q2").height());

        current_page = "2";

    });


    //back button 4

    $(".form_arrow_back4").click(function () {

        //		$('html, body').animate({
        //			scrollTop: $(window).height() * 2
        //		}, 500);

        section1.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height())) + "px)");
        section2.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height())) + "px)");
        section3.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height())) + "px)");
        section4.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height())) + "px)");
        section5.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height())) + "px)");
        section6.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height())) + "px)");

        $("#contact-form").css("height", $("#contact_section_q3").height());

        current_page = "3";

    });

    $(".form_arrow_back5").click(function () {

        //		$('html, body').animate({
        //			scrollTop: $(window).height() * 2
        //		}, 500);

        section1.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height())) + "px)");
        section2.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height())) + "px)");
        section3.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height())) + "px)");
        section4.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height())) + "px)");
        section5.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height())) + "px)");
        section6.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height())) + "px)");

        $("#contact-form").css("height", $("#contact_section_q4").height());

        current_page = "4";

    });
    
    $(".form_arrow_back6").click(function () {

        //		$('html, body').animate({
        //			scrollTop: $(window).height() * 2
        //		}, 500);

        section1.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height()) + parseInt($("#contact_section_q4").height())) + "px)");
        section2.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height()) + parseInt($("#contact_section_q4").height())) + "px)");
        section3.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height()) + parseInt($("#contact_section_q4").height())) + "px)");
        section4.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height()) + parseInt($("#contact_section_q4").height())) + "px)");
        section5.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height()) + parseInt($("#contact_section_q4").height())) + "px)");
        section6.css("transform", "translateY(-" + (parseInt($("#contact_section_q1").height()) + parseInt($("#contact_section_q2").height()) + parseInt($("#contact_section_q3").height()) + parseInt($("#contact_section_q4").height())) + "px)");

        $("#contact-form").css("height", $("#contact_section_q5").height());

        current_page = "5";

    });

    $(window).resize(function () {

        if (current_page == "1") {
            section1.css("transform", "translateY(0px)");
            section2.css("transform", "translateY(0px)");
            section3.css("transform", "translateY(0px)");
            section4.css("transform", "translateY(0px)");
            section5.css("transform", "translateY(0px)");
            section6.css("transform", "translateY(0px)");

            $("#contact-form").css("height", $("#contact_section_q1").height());

            current_page = "1";
        }
        
        if (current_page == "2") {
            form_scroll_q1();
        }
        
        if (current_page == "3") {
            form_scroll_q2();
        }
        
        if (current_page == "4") {
            form_scroll_q3();
        }
        
        if (current_page == "5") {
            form_scroll_q4();
        }
        
        if (current_page == "6") {
            form_scroll_q5();
        }

    });

});




/*---------------------Contact Form----------------------*/


//button section 5

$("#button_ok6").click(function (e) {

    if ($('#check-datenschutz').is(':checked')) {

        $(".checkmark").css("border", "1px solid " + border_color);


        inputq5.attr("value", inputq5.val());

        (function ($, window, document, undefined) {
            'use strict';

            console.log("start submit");

            var $form = $('#contact-form');

            $form.submit(function (e) {
                console.log("start submit 2");
                // remove the error class
                $('.form-group').removeClass('has-error');
                $('.help-block').remove();

                console.log("working 1");

                // get the form data
                var formData = {
                    'q1': $('select[name="form-q1"]').val(),
                    'q2': $('select[name="form-q2"]').val(),
                    'q3': $('select[name="form-q3"]').val(),
                    'q4': $('input[name="form-q4"]').val(),
                    'q5': $('select[name="form-q5"]').val(),
                    'q6': $('select[name="form-q6"]').val()
                };

                console.log("working 2");

                // process the form
                $.ajax({
                    type: 'POST',
                    url: 'submit/questionaire',
                    data: formData,
                    dataType: 'json',
                    encode: true
                }).done(function (data) {

                    console.log("start submit 3 (handele errors)");

                    // display success message
                    $form.html('<div class="alert_box">' + "Congratulations! Your questionaire was sent successfully." + '</div>');
                    //				console.log("success")
                    //				window.open( "https://miltondating.com/roifvnfdsdfsf.pdf");
                    //					}
                }).fail(function (data) {
                    // for debug
                    console.log("submitting failed");
                    console.log(data);
                    //			window.open( "https://miltondating.com/roifvnfdsdfsf.pdf");
                });

                e.preventDefault();
            });
        }(jQuery, window, document));



    } else {

        $(".checkmark").css("border", "1px solid red");

    }

});
