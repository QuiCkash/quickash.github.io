<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link type="text/css" rel="stylesheet" href="//unpkg.com/bootstrap/dist/css/bootstrap.min.css" />
    <!-- BootstrapVue CSS -->
    <link type="text/css" rel="stylesheet" href="//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.css" />
    <title>Load new products</title>
    <style>
      [v-cloak] {
        display: none;
      }
    </style>
  </head>
  <body>
    <div class="container" id="app" v-cloak>
      <div class="row">
        <div class="col-md-12 mt-5">
          <h1 class="text-center">Liste des caissiers</h1>
          <hr>
        </div>
      </div>
      <div class="row" v-if="records.length">
        <div class="col-md-12">
          <table class="table table-borderless">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nom </th>
                <th>Prenom</th>
                <th>User name</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(record, i) in records" :key="record.id">
                <td>{{i + 1}}</td>
                <td>{{record.Nom_Caissier}}</td>
                <td>{{record.Prenom_Caissier}}</td>
                <td>{{record.username_Caissier}}</td>
                <td>
                  <button @click="deleteRecord(record.Id_Caissier)" class="btn btn-sm btn-outline-danger">Supprimer</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- Vuejs -->
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <!-- BootstrapVue js -->
    <script src="//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.js"></script>
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
      var app = new Vue({
        el: '#app',
        data: {
          records: [],
        },
        methods: {
          getRecords(){
            axios({
              url: 'caissier_record.php',
              method: 'get'
            })
            .then(res => {
              // console.log(res)
              this.records = res.data.rows
            })
            .catch(err => {
              console.log(err)
            })
          },
          deleteRecord(id){
            if (window.confirm('Supprimer le compte de ce caissier')) {
              var fd = new FormData()
              fd.append('id', id)
              axios({
                url: 'caissier_delete.php',
                method: 'post',
                data: fd
              })
              .then(res => {
                // console.log(res)
                if (res.data.res == 'success') {
                  alert('delete successfully')
                  app.getRecords();
                }else{
                  alert('error')
                }
              })
              .catch(err => {
                console.log(err)
              })
            }
          },
        },
        mounted: function(){
          this.getRecords()
        }
      })
    </script>
</html>