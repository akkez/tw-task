<template>
    <div id="results">
        <h3>История</h3>
        <div v-if="rows !== false">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>URL</th>
                        <th>Тип поиска</th>
                        <th>Результаты</th>
                        <th>Дата</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="row in rows">
                        <td>
                            <router-link :to="'/detail/' + row.id">{{row.url}}</router-link>
                        </td>
                        <td>
                            <template v-if="row.type == 'image'">Картинки</template>
                            <template v-else-if="row.type == 'link'">Ссылки</template>
                            <template v-else-if="row.type == 'text'">Текст</template>
                            <template v-else>Unknown ({{row.type}})</template>
                        </td>
                        <td>
                            <template v-if="row.resultCount > 0">{{row.resultCount}} шт</template>
                            <template v-else>-</template>
                        </td>
                        <td>{{row.created}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-else>
            <p>Загрузка...</p>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: 'results',
        data() {
            return {
                rows: false,
            }
        },
        created() {
            var _this = this;
            axios({
                url: '/api/results',
            }).then(response => {
                var data = response.data;
                console.log(data);
                _this.rows = data;
            });
        }
    }
</script>

<style scoped>

</style>