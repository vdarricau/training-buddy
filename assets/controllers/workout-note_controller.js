import {Controller} from 'stimulus'

export default class extends Controller {
    static values = {
        workoutId: Number,
    };

    static targets = ['workoutNote'];

    updateNote()
    {
        window.dispatchEvent(new CustomEvent('loadstart'));

        const formData = new FormData();

        formData.append('workout_note_form[note]', this.workoutNoteTarget.value);

        fetch('/client/workout/' + this.workoutIdValue + '/note', {
            method: 'POST',
            body: formData
        }).then(function (response) {
            response.json().then(() => {
                window.dispatchEvent(new CustomEvent('loadend'));
            });
        });
    }
}
