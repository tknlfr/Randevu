<template>

<div class="row">
   <div v-for= "item in data" :key="item.id" class="col-md-4">
            <div class="card appointment-card">
                <span class="appointment-number">Randevu
                     <span class="newAppointment bg-yellow" v-if="item.isActive == 0">Yeni</span>
                    <span class="newAppointment bg-green" v-if="item.isActive ==1">Onayli</span>
                    <span class="newAppointment bg-red" v-if="item.isActive ==2">Iptal Edilen</span>

                </span>
                <div class="appointment-detail">
                    <span> {{item.fullName}}</span>
                    <span>{{item.date}}</span>
                    <span>{{item.working}} </span>

                </div>
                <div class="appointment-buttons" v-if= "!item.isActive">
                    <button @click= "appointmentOkey(item.id)">Onayla</button>
                    <button @click= "appointmentCancel(item.id)" >Iptal Et</button>

                </div>
            </div>
        </div>
        </div>


</template>

<script>
    export default {
        props:['data'],
        created()
        {

        },
        methods:{
            appointmentOkey:function(id){
                this.$emit('updateOkey',id)
            },
            appointmentCancel:function(id){
                this.$emit('updateCancel',id)
            },
        }
    }
</script>

