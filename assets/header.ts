import "./styles/header.scss"
import * as $ from "jquery";

function accountClickEvent() {
    // py-[20px] mt-[190px]
    $(".user_account").on("click", () => {
        if($(".user_account_container").hasClass("active")) {
            $(".user_account_container").css("padding-top", "0px");
            $(".user_account_container").css("padding-bottom", "0px");
            $(".user_account_container").removeClass("active");
        } else {
            $(".user_account_container").css("padding-top", "300px");
            $(".user_account_container").css("padding-bottom", "300px");
            $(".user_account_container").addClass("active");
        }
    });
}

accountClickEvent();