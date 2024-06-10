export default {
    methods: {
        productUrl(product) {
            return route('products.show', product.slug);
        },

        hasBaseImage(product) {
            return product.base_image.length !== 0;
        },

        baseImage(product) {
            if (this.hasBaseImage(product)) {
                return product.base_image.path;
            }

            return `${window.singlebag.baseUrl}/public/frontend/electrobag/img/image-placeholder.png`;
        },
    },
};
