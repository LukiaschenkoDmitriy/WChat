/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

const $ = require("jquery");

function folderClickEvent() {
    $(".files").on("click", () => {
        let block = $(".f_files");

        if (block.css("display") == "none") {
            block.css("display", "block")
        } else {
            block.css("display", "none")
        }
    })
}

folderClickEvent()
