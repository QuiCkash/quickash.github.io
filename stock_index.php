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
          <h1 class="text-center">Liste des produits en stock</h1>
          <hr>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <!-- Ajouter Produits -->
          <div>
            <b-button id="show-btn" @click="showModal('my-modal')">Ajouter Produits</b-button>
            <b-modal ref="my-modal" hide-footer title="Add Records">
              <div>
                <form action="" @submit.prevent="onSubmit">
                  <div class="form-group">
                    <label for="">Nom du produit</label>
                    <input type="text" v-model="nom_prod" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="">Prix HTVA</label>
                    <input type="text" v-model="prix_htva" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="">TVA</label>
                    <input type="text" v-model="tva" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="">Quantity</label>
                    <input type="text" v-model="qte" class="form-control">
                  </div>
                  <div class="form-group">
                    <button class="btn btn-sm btn-outline-info">Valider</button>
                  </div>
                </form>
              </div>
              <b-button class="mt-3" variant="outline-danger" block @click="hideModal('my-modal')">Fermer</b-button>
            </b-modal>
          </div>
          <!-- Update Table Produits -->
          <div>
            <b-modal ref="my-modal1" hide-footer title="Update Record">
              <div>
                <form action="" @submit.prevent="onUpdate">
                  <div class="form-group">
                    <label for="">Nom du produit</label>
                    <input type="text" v-model="edit_nom_prod" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="">Prix HTVA</label>
                    <input type="text" v-model="edit_prix_htva" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="">TVA</label>
                    <input type="text" v-model="edit_tva" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="">Quantity</label>
                    <input type="text" v-model="edit_qte" class="form-control">
                  </div>
                  <div class="form-group">
                    <button class="btn btn-sm btn-outline-info">Enregistrer</button>
                  </div>
                </form>
              </div>
              <b-button class="mt-3" variant="outline-danger" block @click="hideModal('my-modal1')">Fermer</b-button>
            </b-modal>
          </div>
        </div>
      </div>
      <div class="row" v-if="records.length">
        <div class="col-md-12">
          <table class="table table-borderless">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nom du Produit</th>
                <th>Prix HTVA</th>
                <th>TVA</th>
                <th>Quantity</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(record, i) in records" :key="record.id">
                <td>{{i + 1}}</td>
                <td>{{record.Nom_Prod}}</td>
                <td>{{record.Prix_HTVA_Prod}}</td>
                <td>{{record.TVA}}</td>
                <td>{{record.Quantite_Prod}}</td>
                <td>
                  <button @click="deleteRecord(record.Id_Prod)" class="btn btn-sm btn-outline-danger">Supprimer</button>
                  <button @click="editRecord(record.Id_Prod)" class="btn btn-sm btn-outline-info">Modifier</button>
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
          nom_prod: '',
          prix_htva: '',
          tva: '',
          qte: '',
          records: [],
          edit_id: '',
          edit_nom_prod: '',
          edit_prix_htva: '',
          edit_tva: '',
          edit_qte: ''
        },
        methods: {
          showModal(id) {
            this.$refs[id].show()
          },
          hideModal(id) {
            this.$refs[id].hide()
          },
          onSubmit(){
            if (this.nom_prod !== '' && this.prix_htva !== '' && this.tva !== '' && this.qte !== '') {
              var fd = new FormData()
              fd.append('nom_prod', this.nom_prod)
              fd.append('prix_htva', this.prix_htva)
              fd.append('tva', this.tva)
              fd.append('qte', this.qte)
              axios({
                url: 'stock_insert.php',
                method: 'post',
                data: fd
              })
              .then(res => {
                // console.log(res)
                if (res.data.res == 'success') {
                  alert('Product added')
                  this.nom_prod = ''
                  this.prix_htva = ''
                  this.tva = ''
                  this.qte = ''
                  app.hideModal('my-modal')
                  app.getRecords()
                }else{
                  alert('error')
                }
              })
              .catch(err => {
                console.log(err)
              })
            }else{
              alert('empty')
            }
          },
          getRecords(){
            axios({
              url: 'stock_record.php',
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
            if (window.confirm('Delete this record')) {
              var fd = new FormData()
              fd.append('id', id)
              axios({
                url: 'stock_delete.php',
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
          editRecord(id){
            var fd = new FormData()
            fd.append('id', id)
            axios({
              url: 'stock_edit.php',
              method: 'post',
              data: fd
            })
            .then(res => {
              if (res.data.res == 'success') {
                this.edit_id = res.data.row[0]
                this.edit_nom_prod = res.data.row[1]
                this.edit_prix_htva = res.data.row[2]
                this.edit_tva = res.data.row[3]
                this.edit_qte = res.data.row[4]
                app.showModal('my-modal1')
              }
            })
            .catch(err => {
              console.log(err)
            })
          },
          onUpdate(){
            if (this.edit_nom_prod !== '' && this.edit_prix_htva !== '' && this.edit_tva !== '' && this.edit_qte !== '' && this.edit_id !== '') {
              var fd = new FormData()
              fd.append('id', this.edit_id)
              fd.append('nom_prod', this.edit_nom_prod)
              fd.append('prix_htva', this.edit_prix_htva)
              fd.append('tva', this.edit_tva)
              fd.append('qte', this.edit_qte)
              axios({
                url: 'stock_update.php',
                method: 'post',
                data: fd
              })
              .then(res => {
                // console.log(res)
                if (res.data.res == 'success') {
                  alert('Produit update');
                  this.edit_nom_prod = '';
                  this.edit_prix_htva = '';
                  this.edit_tva = '';
                  this.edit_qte = '';
                  this.edit_id = '';
                  app.hideModal('my-modal1');
                  app.getRecords();
                }
              })
              .catch(err => {
                console.log(err)
              })
            }else{
              alert('empty');
            }
          }
        },
        mounted: function(){
          this.getRecords()
        }
      })
    </script>
</html>