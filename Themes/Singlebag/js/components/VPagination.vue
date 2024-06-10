<template>
    <ul class="pagination justify-content-end">
        <li class="page-item" :class="{ disabled: hasFirst }">
            <a class="page-link" :disabled="hasFirst" @click="prev">
                <i class="fi-rs-arrow-small-left"></i>
            </a>
        </li>

        <li v-show="rangeFirstPage !== 1" class="page-item">
            <a class="page-link" @click="goto(1)">
                1
            </a>
        </li>

        <li v-show="rangeFirstPage === 3" class="page-item">
            <a class="page-link" @click="goto(2)">
                2
            </a>
        </li>

        <li
            v-show="rangeFirstPage !== 1 && rangeFirstPage !== 2 && rangeFirstPage !== 3"
            class="page-item disabled"
        >
            <span class="page-link">...</span>
        </li>

        <li
            v-for="page in range"
            :key="page"
            class="page-item"
            :class="{ active: hasActive(page) }"
        >
            <a class="page-link" @click="goto(page)">
                {{ page }}
            </a>
        </li>

        <li
            v-show="rangeLastPage !== totalPage && rangeLastPage !== (totalPage - 1) && rangeLastPage !== (totalPage - 2)"
            class="page-item disabled"
        >
            <span class="page-link">...</span>
        </li>

        <li v-show="rangeLastPage === (totalPage - 2)" class="page-item">
            <a class="page-link" @click="goto(totalPage - 1)">
                {{ totalPage - 1 }}
            </a>
        </li>

        <li v-if="rangeLastPage !== totalPage" class="page-item">
            <a class="page-link" @click="goto(totalPage)">
                {{ totalPage }}
            </a>
        </li>

        <li class="page-item" :class="{ disabled: hasLast }">
            <a class="page-link" :class="{ disabled: hasLast }" @click="next">
                <i class="fi-rs-arrow-small-right"></i>
            </a>
        </li>
    </ul>
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
