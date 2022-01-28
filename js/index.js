class Index {
    constructor() {
        this.base_url = location.protocol + '//' + location.hostname;
        this.backend = this.base_url + '/backend/'
        this.get = 'get.php';
        this.delete = 'delete.php';

        this.id = $('#products');

        this.getProducts();
        this.addEvents();
    }

    getProducts = () => {
        $.ajax({
            url: this.backend + this.get,
            success: (products) => {
                this.loadProducts(products);
            },
            dataType: 'json',
        });
    }

    deleteProducts = () => {
        const ids = this.getIds();

        if (ids.length > 0) {
            $.ajax({
                type: 'POST',
                url: this.backend + this.delete,
                data: {ids: ids},
                success: (products) => {
                    this.clearProducts();
    
                    this.loadProducts(products);
                },
                dataType: 'json',
            });
        }
    }

    getIds = () => {
        let ids = [];

        $('.delete-checkbox:checked').each((i) => {
            const current = $('.delete-checkbox:checked')[i];
            
            ids.push(
                parseInt($(current).val())
            );
        });

        return ids;
    }

    redirect = () => {
        window.location.href = this.base_url + '/add-product.html';
    }

    productTempProps = (product) => {
        const type = product.type;

        let html = {
            'dvd': `<p>${product.size} MB</p>`,
            'book': `<p>${product.weight} KG</p>`,
            'furniture': `<p>${product.height}x${product.width}x${product.length} CM</p>`,
        };

        return html[type];
    }

    productTemp = (product) => {
        const html = `
            <div class="product">
                <input class="delete-checkbox" type="checkbox" value="${product.id}"></input>
                <center>
                    <p>${product.sku}</p>
                    <p>${product.name}</p>
                    <p>${product.price} $</p>
                    ${this.productTempProps(product)}
                </center>
            </div>
        `;

        return html;
    }

    loadProducts = (products) => {
        const id = this.id;

        for (const product of products) {
            const product_html = this.productTemp(product);
            id.append(product_html);
        }
    }

    clearProducts = () => {
        const id = this.id;

        id.empty();
    }

    addEvents = () => {
        $('#delete-product-btn').click(() => {
            this.deleteProducts();
        });
        $('#redirect-product-btn').click(() => {
            this.redirect();
        });
    }
}

let page = new Index();