<template>
    <div class="col-md-8">
        <div class="header-search-wrap">
            <form action="#">
                <input type="text" name="term" id="txt-term" @input="fetchSuggestions()" v-model="filter.term" autocomplete="off" :placeholder="$trans('Search for items...')">
                <select class="custom-select" id="search-category-id" v-model="filter.category_id" @change="get_shop_products()">
                    <option :value="null">{{ $trans('All Categories') }}</option>
                    <option v-for="menu in categories" :key="menu.id" :value="menu.id">{{ menu.name }}</option>
                </select>
                <button @click.prevent="hideSearch()"><i class="fa fa-times"></i></button>
            </form>
            <div class="search-suggestions" v-if="showSuggestions == true" v-click-outside="hideSuggestions">
                <div class="search-suggestions-inner custom-scrollbar">
                    <div class="product-suggestion">
                        <!-- <h6 class="title">{{ $trans('') }}</h6> -->
                        <ul class="list-inline product-suggestion-list"  v-if="suggestions.length > 0" >
                            <li class="list-item" v-for="item in suggestions" :key="item.id">
                                <a :href="'product/' + item.slug + '/' + item.id" class="single-item">
                                    <div class="product-image">
                                        <img style="width:50px;" :src="item.image" @error="$event.target.src='uploads/default.png'" alt="">
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
                        <ul class="list-inline product-suggestion-list" v-else>
                            <li>
                                <h6 class="title">{{ $trans('No Products found') }}</h6>
                            </li>
                        </ul>
                        <a :href="'shop?term=' + filter.term + '&category_id' +  filter.category_id" v-if="suggestion_items > 0" class="more-results">
                            {{ suggestion_items }} more results
                        </a>
                    </div>
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
                filter: {
                    category_id: null,
                    term: '',
                    categories: [],
                    limit: 10,
                    order: 'desc'
                },
                suggestion_items: 0,
                form: {
                    query: this.initialQuery,
                    category: this.initialCategory,
                },
                suggestions: [],
            };
        },
        mounted() {
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
                return !this.categories.includes(this.initialCategory);
            },
    
            shouldShowSuggestions() {
                if (!this.showSuggestions) {
                    return false;
                }
    
                return this.hasAnySuggestion;
            },
    
            moreResultsUrl() {
                if (this.form.category) {
                    return route('categories.products.index', this.form);
                }
    
                return route('products.index', {
                    query: this.form.query
                });
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
                // this.filter.category_id = $('#search-category-id').val();
                this.showSuggestions = true;
                if (this.filter.category_id) this.filter.categories[0] = this.filter.category_id;
                else this.filter.categories = [];
                axios.get('/get_shop_products', {
                        params: this.filter
                    })
                    .then(r => r.data)
                    .then((response) => {
                        this.suggestions = response.data;
                        this.suggestion_items = response.total;
                    }).catch((error) => {});
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
                if (!this.form.query) {
                    return;
                }
    
                if (this.activeSuggestion) {
                    window.location.href = this.activeSuggestion.url;
    
                    this.hideSuggestions();
    
                    return;
                }
    
                if (this.form.category) {
                    window.location.href = route('categories.products.index', this.form);
    
                    return;
                }
    
                window.location.href = route('products.index', {
                    query: this.form.query
                });
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
                if (!this.activeSuggestion) {
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
                if (!this.hasAnySuggestion) {
                    return;
                }
    
                this.activeSuggestion = this.allSuggestions[this.nextSuggestionIndex()];
    
                if (!this.activeSuggestion) {
                    this.activeSuggestion = this.firstSuggestion;
                }
    
                this.adjustSuggestionScrollBar();
            },
    
            prevSuggestion() {
                if (!this.hasAnySuggestion) {
                    return;
                }
    
                if (this.prevSuggestionIndex() === -1) {
                    this.clearActiveSuggestion();
    
                    return;
                }
    
                this.activeSuggestion = this.allSuggestions[this.prevSuggestionIndex()];
    
                if (!this.activeSuggestion) {
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
    
            hideSearch(){
                $('.header-search-menu').hide();
                $('.header-main-menu').show();
            }
        },
    };
    </script>
    
    <style scoped>
        .header-search-wrap{
            position: relative;
            width: 100%;
            display: inline-flex;
        }
         .header-search-wrap form button {
                background: var(--main-theme-color, #f7ba01);
            }
    
            .header-search-wrap form {
                display: flex;
                align-items: center;
                width: 575px;
            }
            .header-search-wrap form input {
                border: 1.5px solid #dfdfdf;
                /* width: 350px; */
                width: 100%;
                max-width: 90%;
                padding: 13px 20px;
                border-radius: 5px 0 0 5px;
                font-size: 12px;
                height: 47px;
            }
            .header-search-wrap form input::placeholder {
                font-size: 12px;
                color: #8b8b8b;
            }
            .header-search-wrap form .custom-select {
                display: inline-block;
                padding: 10px 42px 10px 16px;
                font-size: 12px;
                font-weight: 400;
                line-height: 1.6;
                color: #818181;
                vertical-align: middle;
                background: url("../../../assets/images/icons/arrow_down.png") no-repeat scroll 97.5% center;
                background-color: #fff;
                border: none;
                border-radius: 0;
                box-shadow: none;
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                transition: .3s ease-in-out;
                font-family: 'Rubik', sans-serif;
                width: 170px;
                height: 47px;
                border-top: 1.5px solid #dfdfdf;
                border-bottom: 1.5px solid #dfdfdf;
            }
    
            .header-search-wrap form button {
                flex-grow: 1;
                border: none;
                padding: 0;
                font-size: 16px;
                color: #fff;
                height: 47px;
                line-height: 46px;
                border-radius: 0 5px 5px 0;
                cursor: pointer;
                width: 80px;
            }
            .header-search-wrap form .custom-select option {
                color: #252525;
            }
    
    .search-suggestions {
        position: absolute;
        background: #fff;
        border-bottom: 2px solid #0068e1;
        border-bottom: 2px solid var(--color-primary);
        border-radius: 0 0 2px 2px;
        box-shadow: 0 1px 5px rgba(0, 0, 0, .15);
        z-index: 1050;
        overflow-y: scroll;
        /* max-width: 700px; */
        width: 100%;
        margin: auto;
        max-width: 520px;
    }
    
    .search-suggestions .search-suggestions-inner {
        max-height: 425px;
        background: #fff;
        padding-bottom: 11px;
    }
    
    .search-suggestions .title {
        font-size: 13px;
        font-weight: 400;
        position: relative;
        padding: 6px 20px;
        color: #a6a6a6;
        background: #f9f9f9;
    }
    
    .search-suggestions .title:after {
        position: absolute;
        content: "";
        left: 0;
        top: 7px;
        height: 15px;
        width: 7px;
        background: #0068e1;
        background: var(--color-primary);
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }
    
    .search-suggestions .list-item {
        -webkit-transition: .15s ease-in-out;
        transition: .15s ease-in-out;
    }
    
    .search-suggestions .list-item.active {
        background: #f7f9fa !important;
    }
    
    .category-suggestion .title {
        margin-bottom: 14px;
    }
    
    .category-suggestion+.product-suggestion {
        margin-top: 11px;
    }
    
    .category-suggestion-list .single-item {
        font-size: 14px;
        line-height: 26px;
        display: block;
        max-width: 100%;
        padding: 0 20px;
        color: #6e6e6e;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        vertical-align: top;
    }
    
    .product-suggestion {
        padding-bottom: 3px;
    }
    
    .product-suggestion .title {
        margin-bottom: 14px;
    }
    
    .product-suggestion .more-results {
        font-size: 14px;
        display: block;
        margin: 14px 0 -14px;
        padding: 6px 0;
        color: #a6a6a6;
        background: #f9f9f9;
        text-align: center;
        border-top: 1px solid #ebebeb;
    }
    
    .product-suggestion .more-results:hover {
        color: #0068e1;
        color: var(--color-primary);
    }
    
    .product-suggestion-list .list-item {
        margin-bottom: 4px;
    }
    
    .product-suggestion-list .list-item:last-child {
        margin-bottom: 0;
    }
    
    .product-suggestion-list .single-item {
        position: relative;
        display: -webkit-box;
        display: flex;
        padding: 6px 20px;
    }
    
    .product-suggestion-list .product-image {
        height: 50px;
        width: 50px;
        min-width: 50px;
        border-radius: 2px;
        overflow: hidden;
    }
    
    .product-suggestion-list .product-image .image-placeholder {
        height: 35px;
        width: 35px;
    }
    
    .product-suggestion-list .product-item-info {
        min-width: 0;
        margin-left: 20px;
        -webkit-box-flex: 1;
        flex-grow: 1;
    }
    
    .product-suggestion-list .product-item-info-top {
        display: -webkit-box;
        display: flex;
        -webkit-box-pack: justify;
        justify-content: space-between;
    }
    
    .product-suggestion-list .product-name {
        font-size: 14px;
        display: inline-block;
        max-width: 100%;
        margin-bottom: 3px;
        color: #6e6e6e;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        vertical-align: top;
    }
    
    .product-suggestion-list .product-badge {
        position: relative;
        top: auto;
        right: auto;
        margin: -2px 0 -1px 15px;
    }
    
    .product-suggestion-list em {
        font-weight: 500;
        font-style: inherit;
        color: #0068e1;
        color: var(--color-primary);
    }
    
    @media screen and (max-width:991px) {
        .search-suggestions {
            /* top: 76px;
            left: 15px;
            right: 15px;
            display: none; */
            top:50px;
        }
    
        .search-suggestions .search-suggestions-inner {
            max-height: 359px;
        }
    
        .header-wrap-inner.sticky .search-suggestions {
            top: 71px;
        }
    
        .header-search-sm-form.active+.search-suggestions {
            display: block;
        }
    }
    
    @media screen and (max-width:391px) {
        .header-logo {
            width: 160px;
            padding-left: 5px;
        }
    }
    
    @media screen and (max-width:391px) {
        .header-logo {
            width: 160px;
            padding-left: 5px;
        }
    }
    @media screen and (max-width: 576px) { 
        .header-search-wrap form .custom-select{
       display: none;
     }
    }
    
    </style>
    