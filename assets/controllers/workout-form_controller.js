import {Controller} from 'stimulus';
import Sortable from "sortablejs";

export default class extends Controller {
    static targets = ['componentList', 'orderNumber'];

    connect() {
        this.setOrder();
        this.sortable = Sortable.create(this.componentListTarget, {
            onEnd: this.setOrder.bind(this),
        })
    }

    setOrder() {
        console.log('blablou')
        this.orderNumberTargets.forEach((element, index) => {
            element.value = index;
        })
    }
}
