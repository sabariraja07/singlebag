<template>
    <ul>
        <li v-for="category in categories" :key="category.id">
            <a :href="'category/' + category.slug + '/' + category.id">
                {{ category.name }}
            </a>
            <div v-if="category.categories" class="toggle"></div>
            <category-menu-item v-if="category.categories != null" :categories="category.categories"></category-menu-item>
        </li>
        
    </ul>
</template>
<script>
    export default {
        name: 'category-menu-item',
        props: {
            categories: {
                type: Array,
                required: true
            },
        }
    }
</script>