<template>
    <div class="portlet-body">
    <input v-model="searchFor" class="form-control" @keyup.enter="setFilter" value="">
    <vuetable
              :api-url="url"
              pagination-path=""
              :fields="columns"
              :sort-order="sortOrder"
              :multi-sort="multiSort"
              table-class="table  table-bordered table-striped table-hover"
              ascending-icon="glyphicon glyphicon-chevron-up"
              descending-icon="glyphicon glyphicon-chevron-down"
              pagination-class=""
              pagination-info-class=""
              pagination-component-class=""
              :pagination-component="paginationComponent"
              :append-params="moreParams"
              :per-page="perPage"
              wrapper-class="table-responsive"
              loading-class="loading"
    ></vuetable>
    </div>

</template>


<script>
    import Vuetable from 'vuetable-2/src/components/Vuetable.vue'
    import VuetablePagination from 'vuetable-2/src/components/VuetablePagination.vue'

    Vue.component('vuetable', Vuetable)
    Vue.component('vuetable-pagination', VuetablePagination)
    var tableColumns = ['id', 'nik', 'name' ]
    export default {
        name: 'vue-table',
//        props: ['columns', 'url'],
        data () {
            return {
                url : '/ba',
                columns: tableColumns,
                sortOrder: [{
                    field: 'id',
                    direction: 'asc'
                }],
                paginationComponent: 'vuetable-pagination',
                paginationInfoTemplate: 'แสดง {from} ถึง {to} จากทั้งหมด {total} รายการ',
                perPage: 10,
                searchFor: '',
                moreParams: {},
                multiSort: true
            }
        },
        methods: {
            setFilter () {
                this.moreParams = {
                    filter : this.searchFor
                }
//                this.moreParams = [
//                    'filter=' + this.searchFor
//                ]
                this.$nextTick(function () {
                    this.$broadcast('vuetable:refresh')
                })
            }
        }
    }

</script>



<style>
    .vuetable th.sortable:hover {
        color: #2185d0;
        cursor: pointer;
    }
    .vuetable-pagination {
        margin: 0;
    }
    .vuetable-pagination .btn {
        margin: 2px;
    }
    .vuetable-pagination-info {
        float: left;
        margin-top: auto;
        margin-bottom: auto;
    }
    ul.pagination {
        margin: 0px;
    }
    .vuetable-pagination-component {
        float: right;
    }
    .vuetable-pagination-component li a {
        cursor: pointer;
    }
    [v-cloak] {
        display: none;
    }
    .highlight {
        background-color: yellow;
    }
    .vuetable-detail-row {
        height: 200px;
    }
    .detail-row {
        margin-left: 40px;
    }
    .expand-transition {
        transition: all .5s ease;
    }
    .expand-enter, .expand-leave {
        height: 0;
        opacity: 0;
    }
    /* Loading Animation: */
    .vuetable-wrapper {
        opacity: 1;
        position: relative;
        filter: alpha(opacity=100); /* IE8 and earlier */
    }
    .vuetable-wrapper.loading {
        opacity:0.4;
        transition: opacity .3s ease-in-out;
        -moz-transition: opacity .3s ease-in-out;
        -webkit-transition: opacity .3s ease-in-out;
    }
    .vuetable-wrapper.loading:after {
        position: absolute;
        content: '';
        top: 40%;
        left: 50%;
        margin: -30px 0 0 -30px;
        border-radius: 100%;
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both;
        border: 4px solid #000;
        height: 60px;
        width: 60px;
        background: transparent !important;
        display: inline-block;
        -webkit-animation: pulse 1s 0s ease-in-out infinite;
        animation: pulse 1s 0s ease-in-out infinite;
    }
    @keyframes pulse {
        0% {
            -webkit-transform: scale(0.6);
            transform: scale(0.6); }
        50% {
            -webkit-transform: scale(1);
            transform: scale(1);
            border-width: 12px; }
        100% {
            -webkit-transform: scale(0.6);
            transform: scale(0.6); }
    }
</style>