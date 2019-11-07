$(document).ready(function () {
    // this will handle the click on the btn
    $("#refreshBtn").click(function () {
        $("#result").html("... loading ...");
        getExchange();
    });
});

// get the exchange rate
function getExchange() {
    $.ajax({
        url: "full/refresh", 
    }).done(function (data) {

            data = JSON.parse(data);
            
            if (data.Note != undefined) {
                console.log("Getting the data failed");

                $("#result").html(data.Note);
            }
            else {
                html = `<div class="container">
                            <div class="row">
                                <div class="six columns">From Currency Code:</div>
                                <div class="six columns">${data['Realtime Currency Exchange Rate']['1. From_Currency Code']}</div>
                            </div>
                            <div class="row">
                                <div class="six columns">To Currency Code:</div>
                                <div class="six columns">${data['Realtime Currency Exchange Rate']['3. To_Currency Code']}</div>
                            </div>
                            <div class="row">
                                <div class="six columns">Exchange Rate:</div>
                                <div class="six columns">${data['Realtime Currency Exchange Rate']['5. Exchange Rate']}</div>
                            </div>
                            <div class="row">
                                <div class="six columns">Last Refreshed:</div>
                                <div class="six columns">${data['Realtime Currency Exchange Rate']['6. Last Refreshed']}</div>
                            </div>
                        </div>`;

                $("#result").html(html);

                $.ajax({url: "full/save", data: data}).done(function (ret) { 
                    if (!ret) {
                        console.log("Saving the data failed");
                    }
                });
            }
        }
    );
}
