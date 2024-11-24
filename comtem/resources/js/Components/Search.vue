<script>
// import { Inertia } from '@inertiajs/inertia';
// import {route} from "ziggy-js";
import {Inertia} from "@inertiajs/inertia";
import {route} from "ziggy-js";

export default {
    data() {
        return {
            query: '',
            filteredQueries: [],
        };
    },
    methods: {
        // Handles the search logic and navigates using Vue Router
        handleSearch() {
            console.log("search works");
            //console.log(route('about')); // Should log the correct URL, e.g., '/about'

            const query = this.query.toLowerCase();

            if (['about us', 'aboutus', 'about'].includes(query)) {
                Inertia.visit('/about');
            } else if (query === 'home') {
                Inertia.visit('/');
            } else if (['contacts', 'contact'].includes(query)) {
                Inertia.visit('/contact');
            } else if  (['market', 'markets', 'shop'].includes(query)) {
                Inertia.visit('/market');
            } else {
                alert('Page not found');
            }
        },
    },
};
</script>

<template>
    <div class="container_search">
        <div class="row height d-flex justify-content-center align-items-center">
            <div class="col-md-8">
                <div class="search">
                    <i class="fa fa-search"></i>
                    <form @submit.prevent="handleSearch" id="searchForm" class="form-inline form">
                        <input type="text" id="searchInput" class="form-control" placeholder="Searching..." v-model="query">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                    <ul id="suggestions" class="suggestions-list"></ul>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.search{
    display: flex;
    position: relative;
    box-shadow: 0 0 40px rgba(51, 51, 51, .1);
    align-items: center;
}

.container_search {
    padding: 30px !important;
}

.search input {
    flex-grow: 1;
    height: 60px;
    text-indent: 25px;
    border: 2px solid #d6d4d4;
}

.search form {
    display: flex;
    width: 100%;
}

.search button {
    position: absolute;
    top: 5px;
    right: 5px;
    height: 50px;
    width: 110px;
    background: #420d65;
}

.search button:hover {
    background:#8311cf;
}

</style>
