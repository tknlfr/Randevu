<template>
<div class="container">

    <appointment-item :data="items.data"></appointment-item>

    <div class="row" style="margin-top:10px">
        <div class="col-md-12">
            <pagination :data= "items" @pagination-change-page = "getData">
            </pagination>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        data(){
            return{
                items:{
                    data:[]
                }
            }
        },
        created(){
            this.getData();

        },
        mounted(){
            console.log('Beni cagirdin...');
        },
        methods:{
            getData(page){
                if(typeof page === 'undefined')
                {
                    page = 1;
                }
                console.log(page);
                axios.get('http://127.0.0.1:8000/api/admin/waiting-list/?page='+ page)
                .then((res)=>{
                    console.log(res);
                    this.items = res.data;
                })
            }
        }
    }
</script>

