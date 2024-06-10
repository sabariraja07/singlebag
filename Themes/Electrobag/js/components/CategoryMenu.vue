<template>
    <ul class="categories-menu transparent">
        <li v-for="category in categories" :key="category.id">
            <a :href="'category/' + category.slug + '/' + category.id">
                {{ category.name }}
            </a>
            <div  class="toggle"></div>
            <!-- <category-menu-item v-if="category.children_categories" :categories='category.children_categories'> </category-menu-item> -->
        </li>
    </ul>
</template>
<script>
    import CategoryMenuItem from './CategoryMenuItem.vue';

    export default {
        components: {
            CategoryMenuItem
        },
        props: {
            categories: {
                type: Array,
                required: true
            },
        }
    }
</script>