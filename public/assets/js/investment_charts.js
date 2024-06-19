! function (t) {

    function createCookie(name, value, days) {
        var expires;
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        } else {
            expires = "";
        }
        document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
    }

    function getDate(n) {
        var date = new Date();
        date.setDate(date.getDate() - n);
        return date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear();
    }

    var bulls = [["general", "#758698", "General Bull", "S&P 500"], ["crypto", "#ffc100", "Crypto Bull", "CMC 200"], ["crypto2", "#ff8100", "Crypto Bull", "CMC 200"], ["ecological", "#00d285", "Ecological Bull", "Federal Global Green Bonds"], ["nft", "#e6effb", "NFT Bull", "International NFTs"], ["metaverse", "#bc69fb", "Metaverse Bull", "Metaverse NFTs"], ["crypto", "#ffc100", "Crypto Bull", "CMC 200"], ["commodities", "#495463", "Commodities Bull", "Average Commodities"]];    
    
    for (let h = 0; h < bulls.length; h++) { 
        if (t("#tknSale-"+bulls[h][0]).length > 0) {

        var theme_color = "#2c80ff";
        tnx_labels = [0,0,0,0,0,0,0,0,0,0,0,0];

        let e = document.getElementById("tknSale-"+bulls[h][0]).getContext("2d"),
            o_general = new Chart(e, {
                type: "line",
                data: {
                    labels: tnx_labels,
                    datasets: [{
                        label: bulls[h][2],
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: bulls[h][1],
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: []
                    }, {
                        type: "line",
                        label: bulls[h][3],
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: theme_color.base,
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: []
                    }]

                },
                options: {
                    legend: {
                        display: 1
                    },
                    maintainAspectRatio: !1,
                    tooltips: {
                        enabled: true,
                        mode: 'label',
                        backgroundColor: "#f2f4f7",
                        titleFontSize: 13,
                        titleFontColor: theme_color.heading,
                        titleMarginBottom: 10,
                        bodyFontColor: theme_color.text,
                        bodyFontSize: 14,
                        bodySpacing: 4,
                        yPadding: 15,
                        xPadding: 15,
                        footerMarginTop: 5,
                        displayColors: 1
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: !1,
                                fontSize: 12,
                                fontColor: theme_color.text
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 0,
                                zeroLineColor: "#e9edf3"
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                fontSize: 12,
                                fontColor: theme_color.text,
                                source: "auto"
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 20,
                                zeroLineColor: "#e9edf3"
                            }
                        }]
                    },
                    plugins: {
                        autocolors: false,
                        annotation: {
                            annotations: {
                                box1: {
                                    type: 'box',
                                    xMin: 1,
                                    xMax: 2,
                                    yMin: 50,
                                    yMax: 70,
                                    backgroundColor: 'rgba(255, 99, 132, 1)'
                                }
                            }
                        }
                    }
                }
            });
        t(".popup_"+bulls[h][0]).on("click", function (e) {
            // e.preventDefault();
            var a = t(this),
                r = t(this);

            t.get(r).done(t => {

                //set new labels
                var tnx_labels = data_bulls[bulls[h][0] + "_dates3m"].split(',');

                
                //open asset data
                var asset_data = data_bulls[bulls[h][0] + "_data3m_s"].split(',');
                
                //round to 2 decimals
//                var tnx_data3 = [];
                var asset_data_rounded = [];
                for (i = 0; i < 12; i++) {
                    asset_data_rounded.push(asset_data[i]);
                    if (asset_data_rounded[i] == 0) {
                        asset_data_rounded[i] = null;
                    }
                    if (asset_data_rounded[i] != null) {
                        asset_data_rounded[i] = parseFloat(asset_data_rounded[i]).toFixed(2);
                    }
                }

                //get delta
                var asset_data_rounded_old = asset_data_rounded;
                asset_data_rounded = [];
                let maxVal_data = 11;
                let delta_data = Math.floor(asset_data_rounded_old.length / maxVal_data);
                for (i = 0; i < asset_data_rounded_old.length; i = i + delta_data) {
                    asset_data_rounded.push(asset_data_rounded_old[i]);
                }
                
                
                //Bull data
                // tnx_data
//                var bull_data = $("#"+bulls[h][0]+"_bull_title").attr("data3m_b").split(',');
                var bull_data = data_bulls[bulls[h][0] + "_data3m_b"].split(',');
                
                var bull_data_new = [];
                bull_data_new[0] = asset_data_rounded[0];

                //add asset value to percentage
                for (i = 1; i < 12; i++) {
                    if (bull_data_new[i-1] != null && bull_data_new[i-1] != undefined) {
                        bull_data_new.push( parseFloat(asset_data_rounded[i])+ (bull_data_new[i-1]*((100 + parseFloat(bull_data[i-1])) /100)-bull_data_new[i-1]) * ((100 + parseFloat(bull_data[i])) /100) );
                    } else {
//                        console.log("first element");
                        //the first element
                        bull_data_new.push( (parseFloat(asset_data_rounded[i]) + parseFloat(asset_data_rounded[i]) * parseFloat(bull_data[i]) / 100) );
                    }
                    
                    //prevent bug
                    if (bull_data_new[i] == 0) {
                        bull_data_new[i] = null;
                    }
                    //round array
                    if (bull_data_new[i] != null) {
                         try {
                            bull_data_new[i] = bull_data_new[i].toFixed(2);
                         } catch(err) { 
                             continue;
                         }
                    }
                }
                
                //get delta
                var bull_data_new_old = bull_data_new;
                bull_data_new = [];
                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(bull_data_new_old.length / maxVal_data_bull);
                for (i = 0; i < bull_data_new_old.length; i = i + delta_data_bull) {
                    bull_data_new.push(bull_data_new_old[i]);
                }

                $("#"+bulls[h][0]+"_bull_gains_text span").html("Enter an amount");
                
                //save data
                o_general.data.labels = tnx_labels;
                o_general.data.datasets[0].data = bull_data_new;
                o_general.data.datasets[1].data = asset_data_rounded;
                o_general.update();
            })
        })
        t("#token-number-"+bulls[h][0]+"-bull").on("input", function () {
            current_amount = $(this).val();

            createCookie("plan", bulls[h][2], 10);

            if ($(this).val() < 100) {
                current_amount = 100;
            }
            if ($(this).val() > 1000000) {
                current_amount = 1000000;
            }
            
            // tnx_data
            if ($("#slider-"+bulls[h][0]).val() == 1) {
                $("#"+bulls[h][0]+"_bull_gains_text span").html((parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) + (parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) * data_bulls[bulls[h][0] + "_data3m_b"].split(',')[11] / 100)).toFixed(2));
            }
            if ($("#slider-"+bulls[h][0]).val() == 2) {
                $("#"+bulls[h][0]+"_bull_gains_text span").html((parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) + (parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) * data_bulls[bulls[h][0] + "_data1y_b"].split(',')[5] / 100)).toFixed(2));
            }
            if ($("#slider-"+bulls[h][0]).val() == 3) {
                $("#"+bulls[h][0]+"_bull_gains_text span").html((parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) + (parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) * data_bulls[bulls[h][0] + "_data1y_b"].split(',')[11] / 100)).toFixed(2));
            }
            
            if ($(this).val() == '') {
                current_amount = 0;
                $("#"+bulls[h][0]+"_bull_gains_text span").html("Enter an amount");
            }

        });
        t(document).on('input', '#slider-'+bulls[h][0], function () {

            tnx_labels = [];

            if ($(this).val() == 1) {
                
                //set new labels
                //                chartdate(90);
                tnx_labels = data_bulls[bulls[h][0] + "_dates3m"].split(',');

                //open asset data
                var asset_data = data_bulls[bulls[h][0] + "_data3m_s"].split(',');

                //round to 2 decimals
                var asset_data_rounded = [];
                for (i = 0; i < 12; i++) {
                    asset_data_rounded.push(asset_data[i]);
                    if (asset_data_rounded[i] == 0) {
                        asset_data_rounded[i] = null;
                    }
                    if (asset_data_rounded[i] != null) {
                        asset_data_rounded[i] = parseFloat(asset_data_rounded[i]).toFixed(2);
                    }
                }

                //get delta
                var asset_data_rounded_old = asset_data_rounded;
                asset_data_rounded = [];
                let maxVal_data = 11;
                let delta_data = Math.floor(asset_data_rounded_old.length / maxVal_data);
                for (i = 0; i < asset_data_rounded_old.length; i = i + delta_data) {
                    asset_data_rounded.push(asset_data_rounded_old[i]);
                }


                //Bull data
                var bull_data = data_bulls[bulls[h][0] + "_data3m_b"].split(',');
                var bull_data_new = [];
                bull_data_new[0] = asset_data_rounded[0];

                //add asset value to percentage
                for (i = 1; i < 12; i++) {
                    bull_data_new.push((parseFloat(asset_data_rounded[i]) + parseFloat(asset_data_rounded[i]) * parseFloat(bull_data[i]) / 100));
                    //prevent bug
                    if (bull_data_new[i] == 0) {
                        bull_data_new[i] = null;
                    }
                    //round array
                    if (bull_data_new[i] != null) {
                        bull_data_new[i] = bull_data_new[i].toFixed(2);
                    }
                }

                //get delta
                var bull_data_new_old = bull_data_new;
                bull_data_new = [];
                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(bull_data_new_old.length / maxVal_data_bull);
                for (i = 0; i < bull_data_new_old.length; i = i + delta_data_bull) {
                    bull_data_new.push(bull_data_new_old[i]);
                }

                $("#"+bulls[h][0]+"_bull_gains_text span").html((parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) + (parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) * data_bulls[bulls[h][0] + "_data3m_b"].split(',')[11] / 100)).toFixed(2));
                $("."+bulls[h][0]+"_bull_roi").html(Math.round(data_bulls[bulls[h][0] + "_data3m_b"].split(',')[11])+"%");
            }
            if ($(this).val() == 2) {

//                chartdate2(180);
                //set new labels
                tnx_labels = data_bulls[bulls[h][0] + "_dates1y"].split(',');
                tnx_labels.shift();
                tnx_labels.shift();
                tnx_labels.shift();
                tnx_labels.shift();
                tnx_labels.shift();
                tnx_labels.shift();
                
                //open asset data
                var asset_data = data_bulls[bulls[h][0] + "_data1y_s"].split(',');
                asset_data.shift();
                asset_data.shift();
                asset_data.shift();
                asset_data.shift();
                asset_data.shift();
                asset_data.shift();
                
                //round to 2 decimals
                var asset_data_rounded = [];
                for (i = 0; i < 6; i++) {
                    asset_data_rounded.push(asset_data[i]);
                    if (asset_data_rounded[i] == 0) {
                        asset_data_rounded[i] = null;
                    }
                    if (asset_data_rounded[i] != null) {
                        asset_data_rounded[i] = parseFloat(asset_data_rounded[i]).toFixed(2);
                    }
                }

                //get delta
                var asset_data_rounded_old = asset_data_rounded;
                asset_data_rounded = [];
                let maxVal_data = 6;
                let delta_data = Math.floor(asset_data_rounded_old.length / maxVal_data);
                for (i = 0; i < asset_data_rounded_old.length; i = i + delta_data) {
                    asset_data_rounded.push(asset_data_rounded_old[i]);
                }

                
                //Bull data
                var bull_data = data_bulls[bulls[h][0] + "_data1y_b"].split(',');
                var bull_data_new = [];
                bull_data_new[0] = asset_data_rounded[0];

                //add asset value to percentage
                for (i = 1; i < 6; i++) {
                    bull_data_new.push((parseFloat(asset_data_rounded[i]) + parseFloat(asset_data_rounded[i]) * parseFloat(bull_data[i]) / 100));
                    //prevent bug
                    if (bull_data_new[i] == 0) {
                        bull_data_new[i] = null;
                    }
                    //round array
                    if (bull_data_new[i] != null) {
                        bull_data_new[i] = bull_data_new[i].toFixed(2);
                    }
                }

                //get delta
                var bull_data_new_old = bull_data_new;
                bull_data_new = [];
                let maxVal_data_bull = 6;
                let delta_data_bull = Math.floor(bull_data_new_old.length / maxVal_data_bull);
                for (i = 0; i < bull_data_new_old.length; i = i + delta_data_bull) {
                    bull_data_new.push(bull_data_new_old[i]);
                }

                $("#"+bulls[h][0]+"_bull_gains_text span").html((parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) + (parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) * data_bulls[bulls[h][0] + "_data1y_b"].split(',')[5] / 100)).toFixed(2));
                $("."+bulls[h][0]+"_bull_roi").html(Math.round(data_bulls[bulls[h][0] + "_data1y_b"].split(',')[5])+"%");

            }
            if ($(this).val() == 3) {
//                chartdate(366);
                //set new labels
                tnx_labels = data_bulls[bulls[h][0] + "_dates1y"].split(',');

                //open asset data
                var asset_data = data_bulls[bulls[h][0] + "_data1y_s"].split(',');

                //round to 2 decimals
                var asset_data_rounded = [];
                for (i = 0; i < 12; i++) {
                    asset_data_rounded.push(asset_data[i]);
                    if (asset_data_rounded[i] == 0) {
                        asset_data_rounded[i] = null;
                    }
                    if (asset_data_rounded[i] != null) {
                        asset_data_rounded[i] = parseFloat(asset_data_rounded[i]).toFixed(2);
                    }
                }

                //get delta
                var asset_data_rounded_old = asset_data_rounded;
                asset_data_rounded = [];
                let maxVal_data = 11;
                let delta_data = Math.floor(asset_data_rounded_old.length / maxVal_data);
                for (i = 0; i < asset_data_rounded_old.length; i = i + delta_data) {
                    asset_data_rounded.push(asset_data_rounded_old[i]);
                }

                
                //Bull data
                var bull_data = data_bulls[bulls[h][0] + "_data1y_b"].split(',');
                var bull_data_new = [];
                bull_data_new[0] = asset_data_rounded[0];

                //add asset value to percentage
                for (i = 1; i < 12; i++) {
                    bull_data_new.push((parseFloat(asset_data_rounded[i]) + parseFloat(asset_data_rounded[i]) * parseFloat(bull_data[i]) / 100));
                    //prevent bug
                    if (bull_data_new[i] == 0) {
                        bull_data_new[i] = null;
                    }
                    //round array
                    if (bull_data_new[i] != null) {
                        bull_data_new[i] = bull_data_new[i].toFixed(2);
                    }
                }

                //get delta
                var bull_data_new_old = bull_data_new;
                bull_data_new = [];
                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(bull_data_new_old.length / maxVal_data_bull);
                for (i = 0; i < bull_data_new_old.length; i = i + delta_data_bull) {
                    bull_data_new.push(bull_data_new_old[i]);
                }

                $("#"+bulls[h][0]+"_bull_gains_text span").html((parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) + (parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) * data_bulls[bulls[h][0] + "_data1y_b"].split(',')[11] / 100)).toFixed(2));
                $("."+bulls[h][0]+"_bull_roi").html(Math.round(data_bulls[bulls[h][0] + "_data1y_b"].split(',')[11])+"%");

            }

            if ($("#token-number-"+bulls[h][0]+"-bull").val() == '') {
                current_amount = 0;
                $("#"+bulls[h][0]+"_bull_gains_text span").html("Enter an amount");
            }

            o_general.data.labels = tnx_labels;
            o_general.data.datasets[0].data = bull_data_new;
            o_general.data.datasets[1].data = asset_data_rounded;

            o_general.update();

        });
    }
    }

}(jQuery);
