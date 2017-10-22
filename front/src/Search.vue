<template>
    <div id="search">
        <h3>Поиск</h3>
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <div v-if="error !== false">
                    <div class="alert alert-danger">
                        {{error}}
                    </div>
                </div>
                <div v-if="message !== false">
                    <div class="alert alert-info">
                        {{message}} <router-link v-if="detailId !== false" :to="'/detail/' + this.detailId">Подробнее</router-link>
                    </div>
                </div>

                <form ref="form">
                    <div class="form-group">
                        <label>Адрес сайта</label>
                        <input type="text" v-model="url" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Что искать</label>
                        <select v-model="type" class="form-control">
                            <option value="link">Ссылки</option>
                            <option value="image">Картинки</option>
                            <option value="text">Текст</option>
                        </select>
                    </div>
                    <div v-if="type === 'text'" class="form-group">
                        <label>Текст для поиска</label>
                        <input type="text" v-model="textQuery" class="form-control">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" v-on:click.prevent="submit">
                            Искать
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    var qs = require('qs');

    export default {
        name: 'search',
        data() {
            return {
                url: '',
                type: 'link',
                textQuery: '',
                error: false,
                message: false,
                detailId: false,
            }
        },
        methods: {
            submit: function () {
                this.error = this.message = this.detailId = false;
                if (!this.validate()) {
                    return;
                }
                var _this = this;
                axios({
                    method: 'POST',
                    url: '/api/search',
                    data: qs.stringify({
                        type: this.type,
                        url: this.url,
                        query: this.type === 'text' ? this.textQuery : undefined
                    })
                }).then(response => {
                    var data = response.data;
                    if (data.error) {
                        this.error = data.error;
                        return;
                    }
                    if (data.message) {
                        this.message = data.message;
                        this.detailId = data.id;
                    }
                    console.log(data);
                });
            },
            validate: function () {
                if (this.url.length === 0) {
                    this.error = 'Введите URL сайта';
                    return false;
                }
                if (!/^https?:\/\/[a-z0-9\\.-/]+(.*?)/i.test(this.url)) {
                    this.error = 'Пожалуйста, введите корректный URL (hint: он должен начинаться с http)';
                    return false;
                }
                if (this.type === 'text' && this.textQuery.length === 0) {
                    this.error = 'Не задан текст для поиска';
                    return false;
                }
                return true;
            }
        }
    }
</script>

<style scoped>

</style>