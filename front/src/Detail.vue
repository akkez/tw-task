<template>
    <div id="detail">
        <template v-if="row !== false">
            <h3>{{row.url}}</h3>
            <p>Тип:
                <span v-if="row.type === 'text'">текст</span>
                <span v-if="row.type === 'image'">картинки</span>
                <span v-if="row.type === 'link'">ссылки</span>
                <br>
                Дата: {{row.created}}</p>
            <p>Результатов: {{row.resultCount}}</p>

            <table v-if="row.type === 'image'" class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>img</th>
                    <th>tag</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index) in row.resultValues">
                    <td>{{index}}</td>
                    <td><img :src="item.src"></td>
                    <td>{{item.tag}}</td>
                </tr>
                </tbody>
            </table>

            <table v-if="row.type === 'link'" class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>link</th>
                    <th>title</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index) in row.resultValues">
                    <td>{{index}}</td>
                    <td>{{item.href}}</td>
                    <td>{{item.title}}</td>
                </tr>
                </tbody>
            </table>

            <table v-if="row.type === 'text'" class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>line</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index) in row.resultValues">
                    <td>{{index}}</td>
                    <td>{{item.text}}</td>
                </tr>
                </tbody>
            </table>

        </template>
        <template v-else>Загрузка</template>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: 'detail',
        data() {
            return {
                row: false,
            }
        },
        created() {
            var detailId = this.$route.params.detailId || '';
            var _this = this;
            axios({
                url: '/api/detail?id=' + detailId,
            }).then(response => {
                var data = response.data;
                _this.row = data;
            });
        }
    }
</script>

<style scoped>

</style>