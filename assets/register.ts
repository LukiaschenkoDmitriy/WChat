import "./styles/register.scss";
import * as $ from "jquery";
import * as zxcvbn from "zxcvbn";

const $password: JQuery<HTMLElement>= $("#password");
const $btn: JQuery<HTMLElement> = $(".btn");
const $progressBar: JQuery<HTMLElement> = $('.progress-bar');
const $rep_password: JQuery<HTMLElement> = $("#rep_password");

const disableButton = (btn: JQuery<HTMLElement>) => {
    btn.prop("disabled", true)
        .css("background-color", "#E8E8E8")
        .off("mouseenter mouseleave");
};

const enableButton = (btn: JQuery<HTMLElement>) => {
    btn.prop("disabled", false)
        .css("background-color", "#fff")
        .on("mouseenter", () => btn.css("background-color", "#E8E8E8"))
        .on("mouseleave", () => btn.css("background-color", "#fff"));
};

const updateButtonState = (percentage: number) => {
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
    const password: any = $password.val();
    const result: any = zxcvbn(password);
    updateButtonState(result.score * 25);
});

$rep_password.on("input", () => {
    const passwordValue: any = $("#password").val();
    const repPasswordValue: any = $("#rep_password").val();

    if (passwordValue !== repPasswordValue) {
        disableButton($btn);
    } else {
        enableButton($btn);
    }
});