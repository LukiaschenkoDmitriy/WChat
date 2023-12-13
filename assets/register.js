import "./styles/register.scss"

const $ = require("jquery");
const $password = $("#password");
const $btn = $(".btn");
const $progressBar = $('.progress-bar');
const $rep_password = $("#rep_password");

const disableButton = (btn) => {
    btn.prop("disabled", true)
        .css("background-color", "#E8E8E8")
        .off("mouseenter mouseleave");
};

const enableButton = (btn) => {
    btn.prop("disabled", false)
        .css("background-color", "#fff")
        .on("mouseenter", () => btn.css("background-color", "#E8E8E8"))
        .on("mouseleave", () => btn.css("background-color", "#fff"));
};

const updateButtonState = (percentage) => {
    disableButton($btn);

    if (percentage <= 25) {
        $progressBar.css("background-color", "#f03d30");
    } else if (percentage <= 75) {
        $progressBar.css("background-color", "#fade2a");
        enableButton($btn);
    } else {
        $progressBar.css("background-color", "#58ed62");
        enableButton($btn);
    }

    if ($rep_password.val() === "") {
        disableButton($btn);
    }

    $progressBar.css('width', `${percentage}%`);
    $progressBar.attr('aria-valuenow', percentage);
};

// check password on strong
$password.on("input", () => {
    const password = $password.val();
    const result = zxcvbn(password);
    updateButtonState(result.score * 25);
});

$rep_password.on("input", () => {
    const passwordValue = $("#password").val();
    const repPasswordValue = $("#rep_password").val();

    if (passwordValue !== repPasswordValue) {
        disableButton($btn);
    } else {
        enableButton($btn);
    }
});