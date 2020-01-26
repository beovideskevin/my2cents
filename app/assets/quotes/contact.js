var onloadCallback = function() {
    grecaptcha.render('recaptcha', {
        'sitekey' : '6LdgjdIUAAAAADEbYEz86_p_klKzP8_uSXk3bu2S'
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