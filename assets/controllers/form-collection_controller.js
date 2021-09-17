import {Controller} from 'stimulus'

export default class extends Controller {
    static targets = ['fields', 'field', 'addButton', 'removeButton'];
    static values = {
        prototype: String,
        maxItems: Number,
        itemsCount: Number,
    };

    connect()
    {
        this.index = this.itemsCountValue = this.fieldTargets.length
    }

    addItem(event)
    {
        event.preventDefault()
        let prototype = JSON.parse(this.prototypeValue)
        const newField = prototype.replace(/__name__/g, this.index)
        this.fieldsTarget.insertAdjacentHTML('beforeend', newField)
        this.index++
        this.itemsCountValue++
    }

    removeItem(event)
    {
        event.preventDefault()
        this.fieldTargets.forEach(element => {
            if (element.contains(event.target)) {
                element.remove()
                this.itemsCountValue--
            }
        })
    }

    itemsCountValueChanged()
    {
        if (this.itemsCountValue === 1) {
            this.removeButtonTarget.classList.add('hidden');
        } else {
            this.removeButtonTarget.classList.remove('hidden');
        }

        if (false === this.hasAddButtonTarget || 0 === this.maxItemsValue) {
            return
        }
        const maxItemsReached = this.itemsCountValue >= this.maxItemsValue
        this.addButtonTarget.classList.toggle('hidden', maxItemsReached)
    }
}
