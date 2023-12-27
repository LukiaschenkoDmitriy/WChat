import * as $ from "jquery";

interface FormField {
    label: string;
    inputName: string;
}

export class ModalWindow {
    public static createModalWindow(windowName: string, form: JQuery<HTMLElement>) {
        const modalWindowWrap = $('<div>').addClass('modal-window-wrap');
        const modalWindow = $('<div>').addClass('modal-window create-chat');
        const modalWindowTitle = $('<div>').addClass('modal-window-title');
        const title = $('<div>').addClass('title').text(windowName);
        const exitButton = $('<button>').addClass('exit').text('Exit');

        modalWindowTitle.append(title, exitButton);
        modalWindow.append(modalWindowTitle, form);
        modalWindowWrap.append(modalWindow);

        modalWindowWrap.insertAfter($(".header"));

        exitButton.on('click', function() {
            ModalWindow.removeModalWindow();
        });
    }

    public static removeModalWindow(): void {
        $(".modal-window-wrap").remove();
    }

    public static generateForm(args: FormField[], buttonValue: string, controller: string): JQuery<HTMLElement> {
        const form = $('<form>').attr('action', controller).attr('method', 'post');

        args.forEach(field => {
            const label = $('<label>').text(field.label);
            const input = $('<input>').attr('type', 'text').attr("name", field.inputName);
            form.append(label, input);
        });

        const submitButton = $('<input>').attr('type', 'submit').val(buttonValue);
        form.append(submitButton);

        return form;
    }
}