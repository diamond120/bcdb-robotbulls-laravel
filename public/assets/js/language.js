
        function setCookie(cname, cvalue, exdays) {
          var d = new Date();
          d.setTime(d.getTime() + (exdays*24*60*60*1000));
          var expires = "expires="+ d.toUTCString();
          document.cookie = cname + "=" + cvalue + ";" + expires + ';path=/;domain=robotbulls.com';
        }
        
        function getCookie(cname) {
          var name = cname + "=";
          var decodedCookie = decodeURIComponent(document.cookie);
          var ca = decodedCookie.split(';');
          for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
              c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
              return c.substring(name.length, c.length);
            }
          }
          return "";
        }
    
        var language; 
        function getLanguage() {
            (getCookie("language") == null) ? setLanguage('en') : false;
            $.ajax({ 
            url:  'https://app.robotbulls.com/assets/languages/' +  getCookie("language") + '.json', 
            dataType: 'json', async: false, dataType: 'json', 
            success: function (lang) { language = lang } });
            
            console.log("language var: " + language);
            console.log("language cookie: " + getCookie("language"));
            if(getCookie("language") == "fr") {
                $(".lang-switch-btn").html("FR <em class='ti ti-angle-up'></em>");
            }
            if(getCookie("language") == "en") {
                $(".lang-switch-btn").html("EN <em class='ti ti-angle-up'></em>");
            }
        }
                                
        function setLanguage(lang) {
            setCookie("language", lang, 5);
            language = getCookie("language");
            console.log("language setLanguage var: " + language);
            console.log("language setLanguage cookie: " + getCookie("language"));
            console.log("language setLanguage input: " + lang);
            location.reload();
        }
                                
        $(document).ready(function(){
            
            $(".setLanguage").click(function() {
                setLanguage($(this).attr("name"));
            });
            
            if(getCookie("language") != null){
                language = getCookie("language");
            } 
            getLanguage();
            
            console.log("test");
            
            if(getCookie("language") != null || getCookie("language") == "en") {
                console.log("language.sign_in: "+ language.sign_in);
                
                console.log("test 2");
                
               $(".sign_in").html(language.sign_in);
               $(".phone").html(language.phone);
               $(".email").html(language.email);
               $(".continue").html(language.continue);
               $(".your_phone").attr("placeholder", language.your_phone);
               $(".or_sign_in_with").html(language.or_sign_in_with);
               $(".dont_have_account").text(language.dont_have_account);
               $(".sign_up_here").html(language.sign_up_here);
               $(".phone_verification").attr("placeholder", language.phone_verification);
               $(".remember_me").html(language.remember_me);
               $(".forgot_password").html(language.forgot_password);
               $(".sign_in2").html(language.sign_in2);
               $(".back").html(language.back);
               $(".send_code_again").html(language.send_code_again);
               $(".dont_match_records").html(language.dont_match_records);
               $(".sign_up").html(language.sign_up);
               $(".already_acount").html(language.already_acount);
               $(".sign_up2").html(language.sign_up2);
               
               $(".agree_terms").html(language.agree_terms);
               $(".your_email").attr("placeholder",language.your_email);
               $(".password").attr("placeholder",language.password);
               $(".reset_password").html(language.reset_password);
               $(".reset_password_under_text").html(language.reset_password_under_text);
               $(".send_reset_link").html(language.send_reset_link);
               $(".go_to_login").html(language.go_to_login);
               $(".privacy_and_policy").html(language.privacy_and_policy);
               $(".terms_of_service").html(language.terms_of_service);
               $(".copyright").html(language.copyright);
               $(".419").html(language._);
               
               
               
            //   Platform
            
            
                $(".header1").html(language.header1);
                $(".header2").html(language.header2);
                $(".header3").html(language.header3);
                $(".header4").html(language.header4);
                $(".header5").html(language.header5);
                $(".KYC_button").html(language.KYC_button);
                $(".welcome").html(language.welcome);
                $(".my_profile").html(language.my_profile);
                $(".referral").html(language.referral);
                $(".activity").html(language.activity);
                $(".logout").html(language.logout);
                $(".submit_your_kyc").html(language.submit_your_kyc);
                $(".account_balance").html(language.account_balance);
                $(".account_equity").html(language.account_equity);
                $(".account_overview").html(language.account_overview);
                $(".currency").html(language.currency);
                $(".kyc_application").html(language.kyc_application);
                $(".plan").html(language.plan);
                $(".your_account_status").html(language.your_account_status);
                $(".new_investment").html(language.new_investment);
                $(".submit_kyc").html(language.submit_kyc);
                $(".our_plans").html(language.our_plans);
                $(".our_plans_under_text").html(language.our_plans_under_text);
                $(".account_prediction").html(language.account_prediction);
                $(".six_months").html(language.six_months);
                $(".twelve_months").html(language.twelve_months);
                $(".eighteen_months").html(language.eighteen_months);
                $(".equity_overview").html(language.equity_overview);
                $(".seven_days").html(language.seven_days);
                $(".fiveteen_days").html(language.fifteen_days);
                $(".thirty_days").html(language.thirty_days);
                $(".current_stock_prices").html(language.current_stock_prices);
                $(".recent_transactions").html(language.recent_transactions);
                $(".view_all").html(language.view_all);
                $(".transaction_list").html(language.transaction_list);
                $(".trans_num").html(language.trans_num);
                $(".usd_amount").html(language.usd_amount);
                $(".to").html(language.to);
                $(".Prev").html(language.Prev);
                $(".Next").html(language.Next);
                $(".earn_with").html(language.earn_with);
                $(".more").html(language.more);
                $(".invite_friends_and_family").html(language.invite_friends_and_family);
                $(".referral_title").html(language.referral_title);
                $(".referral_text").html(language.referral_text);
                $(".referral_url").html(language.referral_url);
                $(".referral_under_text_description").html(language.referral_under_text_description);
                $(".referral_lists").html(language.referral_lists);
                $(".user_name").html(language.user_name);
                $(".earn_bonus").html(language.earn_bonus);
                $(".referral_register_date").html(language.referral_register_date);
                $(".no_one_joined_yet").html(language.no_one_joined_yet);
                $(".gains_for_the_past_3months").html(language.gains_for_the_past_3months);
                $(".gains_for_the_past_6months").html(language.gains_for_the_past_6months);
                $(".gains_for_the_past_12months").html(language.gains_for_the_past_12months);
                $(".timespan").html(language.timespan);
                $(".select_amount").html(language.select_amount);
                $(".ten_percent_risked").html(language.ten_percent_risked);
                $(".make_transaction").html(language.make_transaction);
                $(".general_bull").html(language.general_bull);
                $(".general_bull_description").html(language.general_bull_description);
                // $(".general_bull_difference").html(language.general_bull_difference);
                $(".stocks_bull").html(language.stocks_bull);
                $(".stocks_bull_description").html(language.stocks_bull_description);
                // $(".stocks_bull_difference").html(language.stocks_bull_difference);
                $(".crypto_bull").html(language.crypto_bull);
                $(".crypto_bull_description").html(language.crypto_bull_description);
                // $(".crypto_bull_difference").html(language.crypto_bull_difference);
                $(".real_estate_bull").html(language.real_estate_bull);
                $(".real_estate_bull_description").html(language.real_estate_bull_description);
                // $(".real_estate_bull_difference").html(language.real_estate_bull_difference);
                $(".green_bonds_bull").html(language.green_bonds_bull);
                $(".green_bonds_bull_description").html(language.green_bonds_bull_description);
                // $(".green_bonds_bull_difference").html(language.green_bonds_bull_difference);
                $(".ipo_bull").html(language.ipo_bull);
                $(".ipo_bull_description").html(language.ipo_bull_description);
                // $(".ipo_bull_difference").html(language.ipo_bull_difference);
                $(".payment_processing").html(language.payment_processing);
                $(".investment_amounts_to").html(language.investment_amounts_to);
                $(".payment_description").html(language.payment_description);
                $(".select_payment_method").html(language.select_payment_method);
                $(".pay_with_paypal").html(language.pay_with_paypal);
                $(".pay_with_card").html(language.pay_with_card);
                $(".our_payment_address_will_appear_afterwards").html(language.our_payment_address_will_appear_afterwards);
                $(".account_activities_log").html(language.account_activities_log);
                $(".account_activities_log_under_text").html(language.account_activities_log_under_text);
                $(".clear_all").html(language.clear_all);
                $(".account_activities_log_date").html(language.account_activities_log_date);
                $(".account_activities_log_device").html(language.account_activities_log_device);
                $(".account_activities_log_browser").html(language.account_activities_log_browser);
                $(".account_activities_log_ip").html(language.account_activities_log_ip);
                $(".profile_title").html(language.profile_title);
                $(".personal_details").html(language.personal_details);
                $(".settings").html(language.settings);
                $(".full_name").html(language.full_name);
                $(".email_address").html(language.email_address);
                $(".mobile_number").html(language.mobile_number);
                $(".enter_mobile_number").html(language.enter_mobile_number);
                $(".date_of_birth").html(language.date_of_birth);
                $(".nationality").html(language.nationality);
                $(".select_country").html(language.select_country);
                $(".update_profile").html(language.update_profile);
                $(".security_settings").html(language.security_settings);
                $(".save_ativity_log").html(language.save_ativity_log);
                $(".alert_me_unusual_activity").html(language.alert_me_unusual_activity);
                $(".old_password").html(language.old_password);
                $(".new_password").html(language.new_password);
                $(".comfirm_new_password").html(language.comfirm_new_password);
                $(".password_under_text1").html(language.password_under_text1);
                $(".password_under_text2").html(language.password_under_text2);
                $(".two_fator_verifiation").html(language.two_fator_verifiation);
                $(".two_fator_verifiation_under_text").html(language.two_fator_verifiation_under_text);
                $(".enable_2fa").html(language.enable_2fa);
                $(".2fa_current_status").html(language.two_fa_current_status);
                $(".2fa_diabled").html(language.two_fa_diabled);
                $(".2fa_enabled").html(language.two_fa_enabled);
                $(".2fa_step1").html(language.two_fa_step1);
                $(".2fa_step2").html(language.two_fa_step2);
                $(".2fa_add_account").html(language.two_fa_add_account);
                $(".2fa_aount_name").html(language.two_fa_aount_name);
                $(".2fa_key").html(language.two_fa_key);
                $(".2fa_enter_google_auth_code").html(language.two_fa_enter_google_auth_code);
                $(".2fa_enter_the_code_to_verify").attr("placeholder", language.two_fa_enter_the_code_to_verify);
                $(".confirm_2fa").html(language.confirm_2fa);
                $(".2fa_note").html(language.two_fa_note);
                $(".identitiy_verification_kyc").html(language.identitiy_verification_kyc);
                $(".kyc_modal_under_text1").html(language.kyc_modal_under_text1);
                $(".kyc_modal_under_text2").html(language.kyc_modal_under_text2);
                $(".kyc_modal_under_text2_pending").html(language.kyc_modal_under_text2_pending);
                $(".kyc_modal_under_text2_rejected").html(language.kyc_modal_under_text2_rejected);
                $(".kyc_modal_under_text2").html(language.kyc_modal_under_text2_approved);
                
                $(".click_to_proceed").html(language.click_to_proceed);
                $(".kyc_verifiation").html(language.kyc_verifiation);
                $(".kyc_verifiation_under_text").html(language.kyc_verifiation_under_text);
                $(".kyc_small_title1").html(language.kyc_small_title1);
                $(".kyc_small_title1_under_text").html(language.kyc_small_title1_under_text);
                $(".kyc_small_title1_cta").html(language.kyc_small_title1_cta);
                $(".kyc_small_title1_contact_email_text").html(language.kyc_small_title1_contact_email_text);
                $(".kyc_main_title").html(language.kyc_main_title);
                $(".kyc_main_under_title").html(language.kyc_main_under_title);
                $(".kyc_setion1_title").html(language.kyc_setion1_title);
                $(".kyc_setion1_under_title").html(language.kyc_setion1_under_title);
                $(".kyc_setion1_note").html(language.kyc_setion1_note);
                $(".kyc_first_name").html(language.kyc_first_name);
                $(".kyc_last_name").html(language.kyc_last_name);
                $(".kyc_phone_number").html(language.kyc_phone_number);
                $(".kyc_date_of_birth").html(language.kyc_date_of_birth);
                $(".kyc_gender").html(language.kyc_gender);
                $(".kyc_select_gender").html(language.kyc_select_gender);
                $(".kyc_male").html(language.kyc_male);
                $(".kyc_female").html(language.kyc_female);
                $(".kyc_other").html(language.kyc_other);
                $(".kyc_telegram_username").html(language.kyc_telegram_username);
                $(".kyc_your_address").html(language.kyc_your_address);
                $(".kyc_country").html(language.kyc_country);
                $(".kyc_selet_country").html(language.kyc_selet_country);
                $(".kyc_state").html(language.kyc_state);
                $(".kyc_city").html(language.kyc_city);
                $(".kyc_zip").html(language.kyc_zip);
                $(".kyc_address1").html(language.kyc_address1);
                $(".kyc_address2").html(language.kyc_address2);
                $(".kyc_section2_title").html(language.kyc_section2_title);
                $(".kyc_section2_under_title").html(language.kyc_section2_under_title);
                $(".kyc_setion2_note").html(language.kyc_setion2_note);
                $(".kyc_passport").html(language.kyc_passport);
                $(".kyc_id").html(language.kyc_id);
                $(".kyc_driverslicene").html(language.kyc_driverslicene);
                $(".kyc_id_text_title").html(language.kyc_id_text_title);
                $(".kyc_id_text1").html(language.kyc_id_text1);
                $(".kyc_id_text2").html(language.kyc_id_text2);
                $(".kyc_id_text3").html(language.kyc_id_text3);
                $(".kyc_id_text4").html(language.kyc_id_text4);
                $(".passport_text").html(language.passport_text);
                $(".passport_text_proof").html(language.passport_text_proof);
                $(".id_text").html(language.id_text);
                $(".id_text2").html(language.id_text2);
                $(".id_text_proof").html(language.id_text_proof);
                $(".driverslicene_text").html(language.driverslicene_text);
                $(".driverslicene_proof").html(language.driverslicene_proof);
                $(".drag_drop").html(language.drag_drop);
                $(".or").html(language.or);
                $(".select").html(language.select);
                $(".kyc_terms_privacy").html(language.kyc_terms_privacy);
                $(".kyc_checkbox2").html(language.kyc_checkbox2);
                $(".kyc_checkbox3").html(language.kyc_checkbox3);
                $(".kyc_proceed_to_verify").html(language.kyc_proceed_to_verify);
                $(".questionnaire_title").html(language.questionnaire_title);
                $(".more_referral").html(language.more_referral);
                $(".q_close").html(language.q_close);
                $(".q1").html(language.q1);
                $(".q_note").html(language.q_note);
                $(".select_an_option").html(language.select_an_option);
                $(".yes").html(language.yes);
                $(".no").html(language.no);
                $(".q2").html(language.q2);
                $(".q3").html(language.q3);
                $(".q4").html(language.q4);
                $(".q5").html(language.q5);
                $(".q6").html(language.q6);
                $(".q6_over_title").html(language.q6_over_title);
                $(".submit").html(language.submit);
                
                $(".conservative").html(language.conservative);
                $(".moderatly_conservative").html(language.moderatly_conservative);
                $(".moderate").html(language.moderate);
                $(".agressive").html(language.agressive);
                $(".expected_profit").html(language.expected_profit);
                $(".minimum_amount").html(language.minimum_amount);
                $(".rb_commission").html(language.rb_commission);
                $(".risk").html(language.risk);
                $(".expected_roi").html(language.expected_roi);
                $(".3months").html(language.three_months);
                $(".6months").html(language.six_months);
                $(".12months").html(language.twelve_months);
                
                
            }
        });
                            
