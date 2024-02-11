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

let prefix = "/chat/"+$("#sChatId").val();

ChatManager.addEventChangeChatToElements($(".chat"));

(new ModalWindow("Create chat", "/chat/create-chat"))
    .addInput({input_name: "create_chat_name", required:true, label:"Chat name:", input_type: InputType.TEXT, format: "*"})
    .addInput({input_name: "create_avatar", required:false, label:"Avatar:", input_type: InputType.FILE, format: "image/*"})
    .bindToButton($(".create-chat"));

(new ModalWindow("Rename chat", prefix+"/change-name"))
    .addInput({input_name: "new_name", required:true, label:"New name:", input_type:InputType.TEXT, format: "*"})
    .bindToButton($(".change-name"));

(new ModalWindow("Change avatar", prefix+"/change-avatar"))
    .addInput({input_name: "new_avatar", required:true, label:"New avatar:", input_type:InputType.FILE, format: "image/*"})
    .bindToButton($('.change-avatar'));

(new ModalWindow("Delete chat", prefix+"/delete-chat"))
    .bindToButton($('.delete-chat'));

(new ModalWindow("Add member", prefix+"/add-member"))
    .addInput({input_name: "user_id", required:true, label:"User Id:", input_type:InputType.NUMBER, format: "*"})
    .addInput({input_name: "role_id", required:true, label:"Role Id:", input_type:InputType.NUMBER, format: "*"})
    .bindToButton($(".add-member"));

(new ModalWindow("Remove member", prefix+"/remove-member"))
    .addInput({input_name: "user_id", required:true, label:"User Id:", input_type:InputType.NUMBER, format: "*"})
    .bindToButton($(".remove-member"));

(new ModalWindow("Set role", prefix+"/set-role"))
    .addInput({input_name: "user_id", required:true, label:"User Id:", input_type:InputType.NUMBER, format: "*"})
    .addInput({input_name: "role_id", required:true, label:"Role Id:", input_type:InputType.NUMBER, format: "*"})
    .bindToButton($(".set-role"));

