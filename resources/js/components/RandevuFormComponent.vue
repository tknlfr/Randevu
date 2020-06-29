<template >
<div>
    <div v-if="completeForm">
        <div class="container"  >
            <div class="main">
                <h2>Randevu Takip Sistemi</h2>
                <h3>Randevu olusturmak icin bilgileri dogru giriniz</h3>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <ul>
                        <li class="errors" v-for="i in errors" v-bind:key="i">
                            {{ i }}
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" class="form-control" v-model="name" placeholder="Ad Soyad">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" class="form-control" v-model="email" placeholder="Email">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" class="form-control" v-mask="'##-###-###-##-##'" v-model="phone" placeholder="Telefon">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input class="form-control" v-bind:min= "minDate" @change="selectDate" v-model="date" type="date">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <ul class="select-time-ul" >
                        <li v-bind:key="item.id" v-for="item in workingHours" v-bind:class="[item.isActive ? 'active' : 'passive' ]"   class="select-time">
                            <input  v-if="item.isActive" type="radio" v-model="workingHour" v-bind:value="item.id">
                            <span> {{item.hours}}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <textarea v-model="text" id="" placeholder="Not" class="form-control" cols="30" rows="4"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 notification-area">
                        <div class="form-group">
                            <input id="sms" type="radio" v-model="notification_type" value="0">
                            <label for="sms">Sms</label>

                        </div>
                        <div class="form-group">
                            <input id="email" type="radio" v-model="notification_type" value="1">
                            <label for="email">Email</label>

                        </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 notification-area">
                    <button v-on:click= "store" class="btn btn-success">Randevu Olustur</button>
                </div>
            </div>
        </div>

    </div>
    <div>
        <div v-if="!completeForm" class="complete-form">
            <i class="fa fa-check-circle-o" aria-hidden="true"></i>
            <span> Randevunuz basari ile alinmistir.</span>
        </div>
    </div>
</div>

</template>

<script>
   import  io from  'socket.io-client';
    var socket = io('http://localhost:3000');

    export default {
        data(){
            return{
                completeForm:true,
                notification_type:null,
                errors:[],
                workingHour:0,
                name:null,
                email:null,
                phone:null,
                text:null,
                minDate:new Date().toISOString().slice(0,10),
                date:new Date().toISOString().slice(0,10),
                workingHours:[]
            }
        },
            created()
        {
          socket.emit('hello');
        },
        mounted() {
            axios.get('http://127.0.0.1:8000/api/working-hours')
            .then((res)=>{
                this.workingHours=res.data;
            });
        },
        methods:{
            store:function(){
                //var date =new Date();
               // console.log(date.toISOString().slice(0,10));
                //console.log(this.name);
               if(this.notification_type && this.name && this.email && this.validEmail(this.email) && this.phone && this.workingHour!=0)
               {
                   axios.post('http://127.0.0.1:8000/api/appointment-store', {
                       fullName:this.name,
                       phone:this.phone,
                       email:this.email,
                       date:this.date,
                       text:this.text,
                       workingHour:this.workingHour,
                       notification_type:this.notification_type
                   }).then((res)=>{
                       if(res.status)
                       {
                           socket.emit('new_appointment_create');
                           this.completeForm=false;
                       }
                   })

               }
               this.errors=[];
               if(!this.name){
                   this.errors.push('Ad ve Soyad girilmelidir');
               }
                 if(!this.email || !this.validEmail(this.email)){
                   this.errors.push('Email girilmelidir');
               }
                 if(!this.phone){
                   this.errors.push('Telefon numarasi girilmelidir');
               }
                 if(!this.workingHour){
                   this.errors.push('Randevu saati secilmelidir');
               }
                if(!this.notification_type){
                   this.errors.push('Bildirim tipi secilmelidir.');
               }
            },
            selectDate:function(){

                 axios.get('http://127.0.0.1:8000/api/working-hours/?${this.date}'+this.date)
               // axios.get('http://127.0.0.1:8000/api/admin/all/?page='+ page)

            .then((res)=>{
                console.log(res);
                console.log(res.data);
                 console.log($(this.date));
                this.workingHours=res.data;
            });


            },
            validEmail:function(email){
                var re=/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                return re.test(email);
           }

        }
    }
</script>

