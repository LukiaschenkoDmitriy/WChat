import './styles/app.scss';

const $ = require("jquery");

function sideBoardClose(button) {
    button.css("display", "none");
}

function sideBoardOpen(button) {
    button.css("display", "block")
}

function chatFolderClickEvent() {
    $(".files").on("click", () => {
        let files = $(".f_files");
        let settings = $(".f_settings");

        if (files.css("display") != none)

        if (block.css("display") == "none") {
            block.css("display", "block")
        } else {
            block.css("display", "none")
        }
    })
}

function chatOptionClickEvent() {
    $(".settings").on("click", () => {
        let block = $(".f_settings");

        if (block.css("display") == "none") block.css("display", "block");
        else block.css("display", "none");
    })
}