<template>
    <div class="w-50 mx-auto">
        <div class="d-flex flex-row">
            <select v-model="selected">
                <option value="id">Id</option>
                <option value="type">Тип оборудования</option>
                <option value="mask">Маска SN</option>
            </select>
            <input class="w-50" v-model="search" placeholder="Поиск "/>
            <button class="btn btn-info" @click="loadEquipment(1)">Искать</button>
        </div>
        <table>
            <thead>
            <tr>
                <th>id</th>
                <th>Тип оборудования</th>
                <th>Маска SN</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="equipment in equipments">
                <td style="width: 15px">
                    <router-link
                        :to="{ name: 'SingleEquipment', params: { id: equipment.id }}"
                    >
                        {{equipment.id}}
                    </router-link>
                </td>
                <td style="width: 140px">
                    <span
                        v-if="edit.action !== true || edit.id !== equipment.id"
                    >
                        {{equipment.type}}
                    </span>
                    <input
                        class="w-75"
                        v-if="edit.id == equipment.id && edit.action == true"
                        v-model="equipment.type"
                    />
                </td>
                <td style="width: 100px">
                    <span
                        v-if="edit.action !== true || edit.id !== equipment.id"
                    >
                        {{equipment.mask}}
                    </span>
                    <input
                        class="w-75"
                        v-if="edit.id == equipment.id && edit.action == true"
                        v-model="equipment.mask"
                    />
                </td>
                <td style="width: 100px">
                    <button
                        v-if="edit.action == false" @click.prevent="editField(equipment.id)"
                        class="btn btn-warning"
                    >
                        Изменить
                    </button>
                    <button
                        v-if="edit.action == true && edit.id == equipment.id" @click.prevent="changeField(equipment.id, equipment.type, equipment.mask)"
                        class="btn btn-info">
                        Применить
                    </button>
                </td>
                <td style="width: 140px">
                    <button
                        v-if="edit.action == false" @click.prevent="delField(equipment.id)"
                        class="btn btn-danger">
                        Удалить
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
        <table>
            <tbody>
            <tr v-if="create">
                <td v-for="(form, i) in forms" class="d-flex column">
                    <input type="text" placeholder="Тип оборудования" v-model="form.type" />
                    <input placeholder="Маска SN" type="text" v-model="form.mask" />
                    <button class="btn btn-outline-success" v-if="i == forms.length - 1" @click="addInput">Добавить</button>
                    <button class="btn btn-outline-danger" v-if="i !== forms.length - 1" @click="removeInput(form)">Убрать</button>
                </td>
                <td>
                    <span class="text-danger">{{this.error}}</span>
                </td>
            </tr>
            <tr v-if="create">
                <td>
                    <button class="btn btn-success" @click.prevent="store">Создать</button>
                </td>
            </tr>
            <tr>
                <td >
                    <button @click="addField" :class="btnStyle ? 'btn btn-success' : 'btn btn-danger'">{{this.btnAdd}}</button>
                </td>
            </tr>
            <tr>
                <td>
                    <button
                        :disabled="page == 1"
                        @click="nextPage(page, 'prev')"
                    >Предыдущая
                    </button>
                    <span v-for="page in totalPage">
                        <button class="btn btn-light" @click="loadEquipment(page)">{{page}}</button>
                    </span>
                    <button
                        :disabled="totalPage == page"
                        @click="nextPage(page, 'next')"
                    >Следующая
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="mt-4 p-2 card">
            <h4>Гайд по валидации при создании элементов</h4>
            <div>
                <span>Маска SN можно создавать только по этим шаблонам: NAAAAXZXXX, NXXAAXZXaa, XXAAAAAXAA</span>
                <pre>Где:
•	N – цифра от 0 до 9;
•	A – прописная буква латинского алфавита;
•	a – строчная буква латинского алфавита;
•	X – прописная буква латинского алфавита либо цифра от 0 до 9;
•	Z –символ из списка: “-“, “_”, “@”.
</pre>
                <div class="d-flex flex-column">
                    <span>Пример: A0ASDFGAAA, 0O2AAA@1av, 0AAAA0_123</span>
                    <span>Пагинация тут по 5 элементов на странице</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    name: 'Index',
    data: () => ({
        equipments : [],
        forms: [{
            mask: "",
            type: ""
        }],
        edit: {id: 0, action: false},
        error: '',
        classObject: {
            active: true,
            'text-danger': false
        },
        btnStyle: true,
        btnAdd: '+',
        selected: 'type',
        page: 1,
        totalPage: 1,
        create: false,
        search: ''
    }),
    mounted() {
        this.loadEquipment()
    },
    methods: {
        editField(id) {
            this.edit.id = id;
            this.edit.action = !this.edit.action;
        },
        changeField(id, type, mask) {
            axios.put(`/api/equipment/${id}`, {type, mask}, {
                headers: {
                    "Content-type": "application/json"
                }
            }).then(res => {
                if(res.status == 200) {
                    this.edit.action = false
                }
            })
        },
        delField(id) {
            axios.delete(`/api/equipment/${id}`).then(res => {
                this.removeRow(res.data.value)
            })
        },
        async store() {
            let payload = {
                equipments: this.forms
            };
            await axios.post('/api/equipment', payload, {
                headers: {
                    "Content-type": "application/json"
                }
            }).then(res => {
                this.loadEquipment()
                this.create = !this.create
                this.error = ''
                this.btnAdd = '+';
                this.btnStyle = true;

            }).catch(err => {
                this.error = err.response.data.message
                console.log(this.forms)
            })
        },
        addField() {
            this.create = !this.create
            if(this.btnAdd == '+') {
                this.btnAdd = '-';
                this.btnStyle = false;
            } else {
                this.btnAdd = '+';
                this.btnStyle = true;
            }
        },
        addInput() {
            this.forms.push({type: '', mask: ''})
        },
        removeInput(i) {
            const index = this.forms.indexOf(i)
            this.forms.splice(index, 1)
        },
        removeRow(id) {
            const index = this.equipments.findIndex(el => el.id == id)
            delete this.equipments[index];
            this.loadEquipment()
        },
        nextPage(page, count) {
            if(count == 'next') {
                this.page = page+1
            } else {
                this.page = page-1
            }
            this.loadEquipment(this.page)
        },
        async loadEquipment(page) {
            const queries = []
            if(this.search !== '') {
                queries.push(`${this.selected}=${this.search}`)
            }
            queries.push(`page=${page}`)
            const queryStr = queries.length > 0 ? `?${queries.join('&')}` : ''
            await axios.get(`/api/equipment${queryStr}`).then(res => {
                this.equipments = res.data.value.data
                if(!res.data.value.data) {
                    this.equipments = res.data.value
                }
                this.totalPage = res.data.value.last_page
            })
        }
    }
}
</script>

<style scoped>
</style>
