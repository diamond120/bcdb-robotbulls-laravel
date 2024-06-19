    // Toggle content and smooth scroll
    $(".toggle-content-tigger").click(function() {
        var $target = $(this).toggleClass("active").parent().find(".toggle-content").slideToggle();
        $('.toggle-content').not($target).hide();
    });

    // Handle bull data and ROI calculation
    var bulls = [ "general", "crypto", "nft", "metaverse", "ecological", "commodities"];
    bulls.forEach(bull => {
        var element = document.getElementById(`${bull}_bull_title`);
        var data3m_b = data_bulls[bull + "_data3m_b"].split(',').map(Number);
        $(`.${bull}_bull_roi`).text(`${Math.round(data3m_b[data3m_b.length - 1])}%`);
    });

    // Cookie creation function
    function createCookie(name, value, days) {
        var expires = days ? `; expires=${new Date(Date.now() + days * 864e5).toGMTString()}` : "";
        document.cookie = `${escape(name)}=${escape(value)}${expires}; path=/`;
    }

    // Event handler for bull plan cookie
    $("#token-number-btc-bull").on("input", () => {
        console.log("Bull Plan");
        createCookie("plan", "BTC Bull", 10);
    });
    $("#token-number-eth-bull").on("input", () => {
        console.log("Bull Plan");
        createCookie("plan", "ETH Bull", 10);
    });
    $("#token-number-ai-bull").on("input", () => {
        console.log("Bull Plan");
        createCookie("plan", "AI Bull", 10);
    });

    // Tooltip toggle
    $(".tooltip-general, .tooltip-crypto, .tooltip-crypto2, .tooltip-nft, .tooltip-metaverse, .tooltip-btc, .tooltip-ecological, .tooltip-commodities").click(function() {
        var tooltipClass = $(this).attr('class').split(' ')[0].replace('tooltip', 'tooltiptext');
        $(`.${tooltipClass}`).css("visibility", function(i, visibility) {
            return visibility == "hidden" ? "visible" : "hidden";
        });
    });

