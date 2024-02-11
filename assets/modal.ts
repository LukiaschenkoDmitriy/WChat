import "./styles/modal.scss";
import $ from "jquery";

export interface ModalWindowInput {
    label: string,
    input_name: string,
    required: boolean,
    label_type: InputType,
    format: string
}

export enum InputType {
    TEXT = "text",
    NUMBER = "number",
    FILE = "file",
}

export class ModalWindow {

    private inputs: ModalWindowInput[];
    private title: string;
    private form_action: string;

    constructor(title: string, form_action:string) {
        this.inputs = [];
        this.title = title;
        this.form_action = form_action;
    }

    public addInput(input: ModalWindowInput) {
        this.inputs.push(input);
        return this;
    }

    public getFormView() {
       let form_chapter: string = "<form action='"+this.form_action+"' method='post' enctype='multipart/form-data'>";
       this.inputs.forEach(input => {
            let required = input.required ? "required": "";
            form_chapter +=
                "<label for='"+input.input_name+"'>"+input.label+"</label>"+
                "<input type='"+input.label_type+"' maxlength='40' "+required+" accept='"+input.format+"' name='"+input.input_name+"' id='"+input.input_name+"'>"
        });
        form_chapter +=
            "<button type='submit'>Submit</button></form>"

        return form_chapter;
    }

    public getModalView() {
        return `<section id="modal-window">
        <div id="ctn">
            <div id="header">
                <button id="close-modal">X</button>
            </div>
            <div id="title">`+this.title+`</div>
            `+this.getFormView()+`
        </div></section>`
    }

    public bindToButton(button: JQuery<HTMLElement>) {
        button.on("click", () => {
            $("body").prepend(this.getModalView());
            $("#close-modal").on("click", () => {
                $("#modal-window").remove();
            })
        });
        return this;
    }
}