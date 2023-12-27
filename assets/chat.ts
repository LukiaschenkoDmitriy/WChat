import "./styles/chat.scss"
import * as $ from "jquery";
import { ModalWindow } from './ts/ModalWindow';

$(".create_chat_button").on("click", () => {
    ModalWindow.removeModalWindow();
    let form: JQuery<HTMLElement> = ModalWindow.generateForm([
        {label: "Chat name:", inputName:"_chat_name"}
    ], "Create chat", "chat/create-chat");
    
    ModalWindow.createModalWindow("Create chat", form);
})



