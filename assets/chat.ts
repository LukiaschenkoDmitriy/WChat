import "./styles/chat.scss";
import $ from "jquery";
import { InputType, ModalWindow } from "./modal";

class ChatManager {
    static addEventChangeChatToElement(element: JQuery<HTMLElement>) 
    {
        element.on("click", () => {
            let input = element.find(".chat_id");
            let chatId = input.val();

            if (chatId !== undefined && chatId !== null && chatId !== "") {
                let redirectUrl = "/chat/" + chatId;
                window.location.href = window.location.origin + redirectUrl;
            }
        });
    }

    static addEventChangeChatToElements(elements: JQuery<HTMLElement>)
    {
        elements.each(function () {
            ChatManager.addEventChangeChatToElement($(this));
        });
    }
}

ChatManager.addEventChangeChatToElements($(".chat"));
(new ModalWindow("Create chat", "/chat/create-chat"))
    .addInput({input_name: "create_chat_name", required:true, label:"Chat name:", label_type: InputType.TEXT, format: "*"})
    .addInput({input_name: "create_avatar", required:false, label:"Avatar:", label_type: InputType.FILE, format: "image/*"})
    .bindToButton($(".create-chat"));

