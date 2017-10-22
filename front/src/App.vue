<template>
    <div id="app">
        <div class="container">
            <ul class="nav nav-pills">
                <li role="presentation" v-bind:class="{ 'active': hasRoute('/search') || hasRoute('/', true) }">
                    <router-link to="/search">Поиск</router-link>
                </li>
                <li role="presentation" v-bind:class="{ 'active': hasRoute('/results') }">
                    <router-link to="/results">Результаты</router-link>
                </li>
            </ul>
        </div>

        <hr>

        <div class="container">
            <router-view></router-view>
        </div>
        <div class="footer"></div>

    </div>
</template>

<script>
    export default {
        name: 'app',
        data() {
            return {}
        },
        mounted() {
        },
        computed: {},
        methods: {
            hasRoute: function (partial, exact) {
                if (exact) {
                    return this.$route.path === partial;
                }
                return (this.$route.path.indexOf(partial) > -1)
            },
        }
    }
</script>

<style scoped>
    .nav {
        margin-top: 15px;
    }

    .footer {
        height: 60px;
    }
</style>
