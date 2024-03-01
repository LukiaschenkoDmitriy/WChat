export class FormValidate {
    static onInputPhoneValidate(element: JQuery<HTMLElement>): void {
        element.on("input", (event) => {
            const value = element.val()?.toString() || '';
            const sanitizedValue = value.replace(/[^0-9]/g, '');
            element.val(sanitizedValue);
        });
    }

    static onValidateRepeatInputs(element: JQuery<HTMLElement>, repeatedElement: JQuery<HTMLElement>, button: JQuery<HTMLElement>): void
    {
        let checkOnRepeat = () => {
            if (element.val() == repeatedElement.val()) {
                repeatedElement.css("border-color", "green");
                element.css("border-color", "green");
                button.removeAttr("disabled");
            } else{
                repeatedElement.css("border-color", "red");
                element.css("border-color", "red");
                button.attr("disabled", "true");
            }
        }

        repeatedElement.css("border", "1px solid");
        element.css("border", "1px solid");
        element.on("input", checkOnRepeat);
        repeatedElement.on("input", checkOnRepeat);

    }
}
