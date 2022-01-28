class Add {
    constructor() {
        this.base_url = location.protocol + '//' + location.hostname;
        this.backend = this.base_url + '/backend/'
        this.post = 'post.php';

        this.insert = $('#insert');
        this.error = $('#error');

        this.changeForm('dvd');
        this.addEvents();
    }

    postProduct = () => {
        const data = this.collectData();

        $.ajax({
            type: 'POST',
            url: this.backend + this.post,
            data: {product: data},
            success: (output) => {
                if (output.status == 'success') {
                    this.redirect();
                } else {
                    this.showError(output);
                }
            },
            dataType: 'json',
        });
    }

    showError = ({status, message}) => {
        const id = this.error;

        id.html(`<p class=${status}>${message}</p>`);
    }

    collectData = () => {
        const product = {
            sku: $('#sku').val(),
            name: $('#name').val(),
            price: $('#price').val(),
            type: $('#productType').val(),
            size: $('#size').val() || '',
            weight: $('#weight').val() || '',
            height: $('#height').val() || '',
            width: $('#width').val() || '',
            length: $('#length').val() || '',
        };

        return product;
    }

    changeForm = (type) => {
        const id = this.insert;

        let html = {
            'dvd': `
                <div id="dvd">
                    <label for="size">Size (MB)</label>
                    <input type="text" id="size"/>
                    <p>Please, provide size</p>
                </div>
            `,
            'book': `
                <div id="book">
                    <label for="weight">Weight (KG)</label>
                    <input type="text" id="weight"/>
                    <p>Please, provide weight</p>
                </div>
            `,
            'furniture': `
                <div id="furniture">
                    <div>
                        <label for="height">Height (CM)</label>
                        <input type="text" id="height"/>
                    </div>

                    <div>
                        <label for="width">Width (CM)</label>
                        <input type="text" id="width"/> 
                    </div>

                    <div>
                        <label for="length">Length (CM)</label>
                        <input type="text" id="length"/>
                    </div>
                    <p>Please, provide dimensions</p>
                </div>
            `,
        };

        id.html(html[type]);
    }

    redirect = () => {
        window.location.href = this.base_url;
    }

    addEvents = () => {
        $('#productType').change((e) => {
            const type = e.target.value;

            this.changeForm(type);
        });
        $('#save-product-btn').click(() => {
            this.postProduct();
        });
        $('#cancel-product-btn').click(() => {
            this.redirect();
        });
    }
}

let page = new Add();
