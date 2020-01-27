var onloadCallback = function() {
    grecaptcha.render('recaptcha', {
        'sitekey' : '<:SITE_KEY/>'
    });
};

$(document).ready(function () {
    $("#contact").addClass("active");

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