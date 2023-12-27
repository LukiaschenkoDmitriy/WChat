import './styles/app.scss';
import * as $ from "jquery";

function sideBoardClose(block: JQuery<HTMLElement>) {
    block.css("display", "none");
}

function sideBoardOpen(block: JQuery<HTMLElement>) {
    block.css("display", "block")
}

function chatButtonClickEvent(clickButton: JQuery<HTMLElement>, mainBlock: JQuery<HTMLElement>, secondBlock: JQuery<HTMLElement>) {
    clickButton.on("click", () => {
        if (secondBlock.css("display") != "none") {
            sideBoardClose(secondBlock);
        }

        if (mainBlock.css("display") == "none") {
            sideBoardOpen(mainBlock);
        } else {
            sideBoardClose(mainBlock);
        }
    })
}

let file_button: JQuery<HTMLElement> = $(".files");
let setting_button: JQuery<HTMLElement>  = $(".settings");

let file_block: JQuery<HTMLElement>  = $(".f_files");
let setting_block: JQuery<HTMLElement>  = $(".f_settings");

chatButtonClickEvent(file_button, file_block, setting_block);
chatButtonClickEvent(setting_button, setting_block, file_block);