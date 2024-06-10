<template>
    <div class="row electrobag-pagination">
        <div class="col-sm-3 hidden-xs">
            <a class="button size-1 style-5 disabled" v-if="hasFirst" href="javascript:void(0);"
                style="background-color:#e4e4e4;">
                <span class="button-wrapper">
                    <span class="icon"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
                    <span class="text">prev page</span>
                </span>
            </a>
            <a class="button size-1 style-5" v-else @click="prev">
                <span class="button-wrapper">
                    <span class="icon"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
                    <span class="text">prev page</span>
                </span>
            </a>
        </div>
        <div class="col-sm-6 text-center">
            <div class="pagination-wrapper">
                <a v-show="rangeFirstPage !== 1" class="pagination" @click="goto(1)">
                    1
                </a>

                <a class="pagination" v-show="rangeFirstPage === 3" @click="goto(2)">
                    2
                </a>

                <span class="pagination"
                    v-show="rangeFirstPage !== 1 && rangeFirstPage !== 2 && rangeFirstPage !== 3">...</span>

                <a v-for="page in range" :key="page" class="pagination" :class="{ active: hasActive(page) }"
                    @click="goto(page)">
                    {{ page }}
                </a>

                <span class="pagination"
                    v-show="rangeLastPage !== totalPage && rangeLastPage !== (totalPage - 1) && rangeLastPage !== (totalPage - 2)">...</span>

                <a class="pagination" v-show="rangeLastPage === (totalPage - 2)" @click="goto(totalPage - 1)">
                    {{ totalPage - 1 }}
                </a>

                <a v-if="rangeLastPage !== totalPage" class="pagination" @click="goto(totalPage)">
                    {{ totalPage }}
                </a>
            </div>
        </div>
        <div class="col-sm-3 hidden-xs text-right">
            <a class="button size-1 style-5" v-if="!hasLast" @click="next">
                <span class="button-wrapper">
                    <span class="icon"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                    <span class="text">Next page</span>
                </span>
            </a>
            <a class="button size-1 style-5 disabled" v-else href="javascript:void(0);"
                style="background-color:#e4e4e4;">
                <span class="button-wrapper">
                    <span class="icon"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                    <span class="text">Next page</span>
                </span>
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            totalPage: Number,
            currentPage: Number,
            rangeMax: {
                type: Number,
                default: 3,
            },
        },

        mounted() {
            if (this.currentPage > this.totalPage) {
                this.$emit('page-changed', this.totalPage);
            }
        },

        computed: {
            rangeFirstPage() {
                if (this.currentPage === 1) {
                    return 1;
                }

                if (this.currentPage === this.totalPage) {
                    if ((this.totalPage - this.rangeMax) < 0) {
                        return 1;
                    }

                    return this.totalPage - this.rangeMax + 1;
                }

                return this.currentPage - 1;
            },

            rangeLastPage() {
                return Math.min(this.rangeFirstPage + this.rangeMax - 1, this.totalPage);
            },

            range() {
                let rangeList = [];

                for (let page = this.rangeFirstPage; page <= this.rangeLastPage; page += 1) {
                    rangeList.push(page);
                }

                return rangeList;
            },

            hasFirst() {
                return this.currentPage === 1;
            },

            hasLast() {
                return this.currentPage === this.totalPage;
            },
        },

        methods: {
            prev() {
                this.$emit('page-changed', this.currentPage - 1);
            },

            next() {
                this.$emit('page-changed', this.currentPage + 1);
            },

            goto(page) {
                if (this.currentPage !== page) {
                    this.$emit('page-changed', page);
                }
            },

            hasActive(page) {
                return page === this.currentPage;
            },
        },
    };

</script>
