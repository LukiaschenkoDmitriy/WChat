export class ChatManager {
    static offDefaultSubmitForm(submitButton: JQuery<HTMLElement>) {
        submitButton.on('submit', async (e) => {
            e.preventDefault();
    
            const form = e.target;
            if (form instanceof HTMLFormElement) {
                const formData = new FormData(form);

                try {
                    const response = await fetch(form.action, {
                        method: form.method,
                        body: formData
                    });
                    console.log({"Response status:": "202"});
                } catch (error) {
                    console.error('Network error:', error);
                }
            }
        });
    }
}