import './styles/app.scss';

const $ = require("jquery");

function sideBoardClose(block) {
    block.css("display", "none");
}

function sideBoardOpen(block) {
    block.css("display", "block")
}

function chatButtonClickEvent(clickButton, mainBlock, secondBlock) {
    clickButton.on("click", () => {
        if (secondBlock.css("display") != "none") {
            sideBoardClose(secondBlock);
        }

        if (mainBlock.css("display") == "none") {
            sideBoardOpen(mainBlock);
        } else {
            sideBoardClose(mainBlock);
        }

        console.log("123");
    })
}

let file_button = $(".files");
let setting_button = $(".settings");

let file_block = $(".f_files");
let setting_block = $(".f_settings");

chatButtonClickEvent(file_button, file_block, setting_block);
chatButtonClickEvent(setting_button, setting_block, file_block);