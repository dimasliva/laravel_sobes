<template>
    <div>
        <table>
            <thead>
            <tr>
                <th>{{equipment.id}}</th>
                <th>{{equipment.type}}</th>
                <th>{{equipment.mask}}</th>
            </tr>
            </thead>
        </table>
        <button @click="$router.go(-1)">Главная</button>
    </div>
</template>

<script>
import axios from 'axios'
export default {
    name: "SingleEquipment",
    data: () => ({
        equipment: [],
    }),
    mounted() {
        this.loadPost(this.$route.params.id)
    },
    methods: {
        loadPost(id) {
            axios.get(`/api/equipment/${id}`).then(res => {
                this.equipment = res.data
            })
        }
    }
}
</script>

<style scoped>

</style>
