<template>
    <select class="SelectBox">
        <slot></slot>
    </select>
</template>
<script>
    export default {
        props: ['options', 'value'],
        mounted: function () {
            var vm = this
            $(this.$el)
                .val(this.value)
                // init select2
                .select2({
                    data: this.options
                })
                .trigger('change')
                // emit event on change.
                .on('change', function () {
                    vm.$emit('input', this.value)
                });
        },
        watch: {
            value: function (value) {
                // update value
                $(this.$el).val(value).trigger('change');
                this.$emit('change', value);
            },
            options: function (options) {
                // update options
                $(this.$el).select2({
                    data: options
                });
            }
        },
        destroyed: function () {
            $(this.$el).off().select2('destroy')
        }
    }

</script>
