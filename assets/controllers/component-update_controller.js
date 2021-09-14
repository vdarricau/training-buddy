import {Controller} from 'stimulus'

export default class extends Controller {
    static values = {
        componentId: Number,
        componentStatus: String,
    };

    static targets = ['componentCheckbox', 'componentNote']

    updateComponent()
    {
        const formData = new FormData();

        formData.append('component_update[status]', this.componentCheckboxTarget.checked ? 'done' : 'pending');
        formData.append('component_update[note]', this.componentNoteTarget.value);

        fetch('/client/workout/component/' + this.componentIdValue, {
            method: 'POST',
            body: formData
        }).then(function (response) {
            response.json().then((json) => {
                console.log(json)
            })
        })
    }
}
