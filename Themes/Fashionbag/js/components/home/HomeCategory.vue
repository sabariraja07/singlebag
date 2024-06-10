<template>
    <div class="categories-dropdown-wrap style-2 font-heading mt-30">
        <div class="d-flex categori-dropdown-inner">
            <ul class="category_list_view">
                <li v-for="category in categories.slice(0,10)" :key="category.id">
                    <a :href="'category/' + category.slug + '/' + category.id">
                        <img :src="category.url" @error="$event.target.src='uploads/default.png'" alt="" />
                        {{ category.name }}
                    </a>
                </li>
            </ul>
        </div>
        <div v-if="categories.length > 10" class="more_slide_open" style="display: none">
            <div class="d-flex categori-dropdown-inner">
                <ul class="category_list_view">
                    <li v-for="category in categories.slice(10, categories.length)" :key="category.id">
                        <a :href="'category/' + category.slug + '/' + category.id">
                            <img :src="category.url" @error="$event.target.src='uploads/default.png'" alt="" />
                            {{ category.name }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div v-if="categories.length > 10" class="more_categories">
            <span class="icon"></span>
            <span class="heading-sm-1">{{ $trans('Show more...') }}</span>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['categories'],
    };

</script>
