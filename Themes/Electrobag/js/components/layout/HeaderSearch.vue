<template>
    <div class="search-style-2 header-search">
         <form  action="#">
            <div class="search-submit">
                <i class="fa fa-search" aria-hidden="true"></i>                
            </div>
            <input class="simple-input style-1" name="term" id="txt-term" type="text" @input="fetchSuggestions()" v-model="filter.term" autocomplete="off" :placeholder="$trans('Search for items...')" />
        </form>
        
         <div class="search-suggestions" v-if="suggestions.length > 0" v-click-outside="hideSuggestions">
            <div class="search-suggestions-inner custom-scrollbar">
                <div class="product-suggestion">
                    <h6 class="title" style="display: none;">{{ $trans('Add To Cart') }}</h6>
                    <ul class="list-inline product-suggestion-list">
                        <li class="list-item"  v-for="item in suggestions" :key="item.id">
                            <a :href="'product/' + item.slug + '/' + item.id"
                                class="single-item">
                                <div class="product-image">
                                    <img :src="item.image" @error="$event.target.src='uploads/default.png'" alt="" style="width: -webkit-fill-available;">
                                </div>
                                <div class="product-item-info">
                                    <div class="product-item-info-top">
                                        <h6 class="product-name">{{item.title}}</h6>
                                    </div>
                                    <div class="product-price" v-if="item.price">{{$formatPrice(item.price.selling_price)}}</div>
                                </div>
                            </a>
                        </li>
                    </ul> 
                    <a :href="'shop?term=' + filter.term + '&category_id' +  filter.category_id"
                        v-if="suggestion_items > 0"
                        class="more-results">
                        {{ suggestion_items }} more results
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    // import ProductHelpersMixin from '../../mixins/ProductHelpersMixin';
    import axios from 'axios';

    export default {
        // components: { VueSelect },
        // mixins: [
        //     ProductHelpersMixin,
        // ],

        props: ['categories'],

        data() {
            return {
                activeSuggestion: null,
                showSuggestions: false,
                filter:{
                    category_id: null,
                    term: '',
                    categories: [],
                    limit:10,
                    order:'desc'
                },
                suggestion_items: 0,
                form: {
                    query: this.initialQuery,
                    category: this.initialCategory,
                },
                suggestions: [],
            };
        },
        mounted(){
            // $('.select-active').select2().on("change", function() {
            //     self.onSelectChange(this.value);
            // });
            $(".select-active").change(function () {
                const val = $(this).find("option:selected").val();
                self.selected = val;
            });
        },

        computed: {
            initialCategoryIsNotInCategoryList() {
                return ! this.categories.includes(this.initialCategory);
            },

            shouldShowSuggestions() {
                if (! this.showSuggestions) {
                    return false;
                }

                return this.hasAnySuggestion;
            },

            moreResultsUrl() {
               
                return route('products.index', { query: this.form.query });
            },

            hasAnySuggestion() {
                return this.suggestions.products.length !== 0;
            },

            allSuggestions() {
                return [...this.suggestions.categories, ...this.suggestions.products];
            },

            firstSuggestion() {
                return this.allSuggestions[0];
            },

            lastSuggestion() {
                return this.allSuggestions[this.allSuggestions.length - 1];
            },
        },

        watch: {
            'filter.term': function (newQuery) {
                if (newQuery === '') {
                    this.clearSuggestions();
                } else {
                    this.showSuggestions = true;

                    this.fetchSuggestions();
                }
            },
        },

        methods: {
            changeCategory(category) {
                this.form.category = category;

                this.fetchSuggestions();
            },

            fetchSuggestions() {
                if(this.filter.category_id) this.filter.categories[0] = this.filter.category_id;
                else this.filter.categories = [];
                axios.get('/get_shop_products', { params : this.filter})
                .then(r => r.data)
                .then((response) => {
                    this.suggestions = response.data;
                    this.suggestion_items = response.total;
                }).catch((error) => {
                });
                // $.ajax({
                //     method: 'GET',
                //     url: route('suggestions.index', this.form),
                // }).then((suggestions) => {
                //     this.suggestions.categories = suggestions.categories;
                //     this.suggestions.products = suggestions.products;
                //     this.suggestions.remaining = suggestions.remaining;

                //     this.clearActiveSuggestion();
                //     this.resetSuggestionScrollBar();
                // });
            },

            search() {
                if (! this.form.query) {
                    return;
                }

                if (this.activeSuggestion) {
                    window.location.href = this.activeSuggestion.url;

                    this.hideSuggestions();

                    return;
                }

               
                window.location.href = route('products.index', { query: this.form.query });
            },

            clearSuggestions() {
                this.suggestions.categories = [];
                this.suggestions.products = [];
            },

            hideSuggestions(e) {
                this.showSuggestions = false;

                this.clearActiveSuggestion();
            },

            isActiveSuggestion(suggestion) {
                if (! this.activeSuggestion) {
                    return false;
                }

                return this.activeSuggestion.slug === suggestion.slug;
            },

            changeActiveSuggestion(suggestion) {
                this.activeSuggestion = suggestion;
            },

            clearActiveSuggestion() {
                this.activeSuggestion = null;
            },

            nextSuggestion() {
                if (! this.hasAnySuggestion) {
                    return;
                }

                this.activeSuggestion = this.allSuggestions[this.nextSuggestionIndex()];

                if (! this.activeSuggestion) {
                    this.activeSuggestion = this.firstSuggestion;
                }

                this.adjustSuggestionScrollBar();
            },

            prevSuggestion() {
                if (! this.hasAnySuggestion) {
                    return;
                }

                if (this.prevSuggestionIndex() === -1) {
                    this.clearActiveSuggestion();

                    return;
                }

                this.activeSuggestion = this.allSuggestions[this.prevSuggestionIndex()];

                if (! this.activeSuggestion) {
                    this.activeSuggestion = this.lastSuggestion;
                }

                this.adjustSuggestionScrollBar();
            },

            nextSuggestionIndex() {
                return this.currentSuggestionIndex() + 1;
            },

            prevSuggestionIndex() {
                return this.currentSuggestionIndex() - 1;
            },

            currentSuggestionIndex() {
                return this.allSuggestions.indexOf(this.activeSuggestion);
            },

            adjustSuggestionScrollBar() {
                this.$refs.searchSuggestionsInner.scrollTop = this.$refs[this.activeSuggestion.slug][0].offsetTop - 200;
            },

            resetSuggestionScrollBar() {
                this.$refs.searchSuggestionsInner.scrollTop = 0;
            },
        },
    };
</script>
