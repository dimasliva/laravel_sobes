<template>
</template>

<script>
import axios from 'axios';
export default {
    data: () => ({
        equipments : []
    }),
    mounted() {
        this.loadEquipment()
    },
    methods: {
        loadEquipment() {
            axios.get('/api/equipment').then(res => {
                this.equipments = res.data
                console.log(res.data)
            })
        }
    }
}
</script>
