<template>
<ul class="categories-menu home-cat">
    <li v-for="category in categories" :key="category.id">
        <a :href="'category/' + category.slug + '/' + category.id" class="category-link">
        <img :src="category.url" @error="$event.target.src='uploads/default.png'" alt=""/>
        {{ category.name }}
        </a>
        <div class="arrow"></div>
    </li>
</ul>    
</template>
<style scoped>
.home-cat li{
    align-items: center;
    display: flex;
}
.category-link{
    align-items: center;
    display: flex;
    justify-items: center;
    /* border-radius: 0.375rem; 
    margin-left: -0.5rem; */
}
.category-link img{
    width: 45px;
    height: 45px;
    margin-right: 8px;
    border-radius: 50%;
}
</style>

<script>
    export default {
        props: ['categories'],
    };

</script>
