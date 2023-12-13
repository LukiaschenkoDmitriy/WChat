import "./styles/register.scss"

const $ = require("jquery");

function btnOff(btn) {
    btn.attr("disabled");
    btn.css("background-color", "#E8E8E8");
    btn.off("mouseenter").off("mouseleave");
}

function btnOn(btn) {
    btn.removeAttr("disabled");
    btn.css("background-color", "#fff")
    btn.on("mouseenter",() => {
        btn.css("background-color", "#E8E8E8")
    });
    btn.on("mouseleave", () => {
        btn.css("background-color", "#fff")
    })
}

// check password on strong
$("#password").on("input", () => {
    const password = $("#password").val();

    const result = zxcvbn(password)

    const progressBar = $('.progress-bar');

    function updateProgress(percentage) {
        const btn = $(".btn");

        btnOff(btn);

        if (percentage <= 25) progressBar.css("background-color", "#f03d30");
        else {
            progressBar.css("background-color", "#fade2a")
            btnOn(btn);
        }

        progressBar.css('width', percentage + '%');
        progressBar.attr('aria-valuenow', percentage);
    }
    
    updateProgress(result.score * 25);
})