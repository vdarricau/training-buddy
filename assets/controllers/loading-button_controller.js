import {Controller} from 'stimulus'

export default class extends Controller {
    static targets = ['button'];

    connect()
    {
        window.addEventListener('loadstart', this.loadstart.bind(this));
        window.addEventListener('loadend', this.loadend.bind(this));
    }

    loadstart()
    {
        this.buttonTargets.forEach((element) => {
            element.classList.add('btn-loading');
            element.classList.add('disabled');
        });
    }

    loadend()
    {
        this.buttonTargets.forEach((element) => {
            element.classList.remove('btn-loading');
            element.classList.remove('disabled');
        });
    }
}
