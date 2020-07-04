$(document).ready(function () {
    // this will handle the click on the btn
    $("#refreshBtn").click(function () {
        $("#result").html("... loading ...");
        getExchange();
    });

    $("#contactForm").submit(function () {
        if ($("#exampleEmailInput").val() == "" || $("#exampleSubjectInput").val() == "" ||
            $("#exampleMessage").val() == "")
        {
            alert("Fill all the fields, please");
            return false;
        }

        return true;
    });
});

var onloadCallback = function() {
    grecaptcha.render('recaptcha', {
        'sitekey' : '<:SITE_KEY/>'
    });
};

// get the exchange rate
function getExchange() {
    $.ajax({
        url: "btc/refresh", 
    }).done(function (data) {
        data = JSON.parse(data);
        
        if (data.Note != undefined) {
            console.log("Getting the data failed");

            $("#result").html(data.Note);
        }
        else {
            data = data['Realtime Currency Exchange Rate'];
            html = `<div class="container">
                        <div class="row">
                            <div class="six columns">From Currency Code:</div>
                            <div class="six columns">${data['1. From_Currency Code']}</div>
                        </div>
                        <div class="row">
                            <div class="six columns">To Currency Code:</div>
                            <div class="six columns">${data['3. To_Currency Code']}</div>
                        </div>
                        <div class="row">
                            <div class="six columns">Exchange Rate:</div>
                            <div class="six columns">${data['5. Exchange Rate']}</div>
                        </div>
                        <div class="row">
                            <div class="six columns">Last Refreshed:</div>
                            <div class="six columns">${data['6. Last Refreshed']}</div>
                        </div>
                    </div>`;

            $("#result").html(html);
        }
    });
}
