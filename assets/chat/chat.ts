import "./chat.scss";
import $ from "jquery";
import { MercureManager } from "../ts/MercureManager";

let prefix = "/chat/"+$("#sChatId").val();

document.addEventListener("DOMContentLoaded", () => {
    const action = "/mercure/post-message";
    const errorMessage = "Something is wrong with mercure post messasge"
    const callback: ((event: MessageEvent) => any) = (event) => {
        console.log(event.data);
    }
    MercureManager.mercureFetch(action, prefix, callback, errorMessage);
});