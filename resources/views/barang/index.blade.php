<!DOCTYPE html>
<html>

<head>
    <title>List Barang</title>
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
</head>

<style>
* {
    font-family: sans-serif;
    color: white;
}

body {
    margin: 0;
    background-image: linear-gradient(to right, #8360c3, #2ebf91);
    font-family: sans-serif;
    font-weight: 100;
}

h1 {
    text-align: center;
    margin-top: 60px;
    font-weight: bold;
}

.container {
    justify-content: center;
    align-items: center;
}

table {
    margin: auto;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    text-align: center;
}

th,
td {
    padding: 15px;
    background-color: rgba(255, 255, 255, 0.2);
    color: #fff;
}

thead th {
    background-color: #55608f;
}

tbody tr:hover {
    background-color: rgba(255, 255, 255, 0.3);
}

td:hover {
    background-color: rgba(255, 255, 255, 0.2);
}
</style>

<body>
    <h1>List Barang</h1>
    <div class="container" id="appVue">
        <div class="modal fade" id="modalTambahData" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button @click.prevent="closeModal" type="button" class="close btn-danger rounded border-danger"
                            data-dismiss="modal">×</button>
                        <h4 class="modal-title">Tambah Barang</h4>
                    </div>

                    <div class="modal-body">
                        <form role="form">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nama</label>
                                    <input v-model="nama" type="text" class="form-control" placeholder="Tulis Barang">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Jumlah</label>
                                    <input v-model="jumlah" type="text" class="form-control" placeholder="Tulis Jumlah">
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                                <button @click.prevent="storeBarang" type="submit"
                                    class="btn btn-primary mt-3">Submit</button>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button @click.prevent="closeModal" type="button" class="btn btn-danger"
                            data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <input type="text" class="form-control mt-3" style="margin-left: 500px;" placeholder="Search"
                    v-model="search" v-on:keyup="getData()">
            </div>
            <div class="col-md-12">
                <br>
                <button style="margin-left: 560px;" class="btn btn-lg btn-primary mb-3"
                    @click.prevent="tambahData">Tambah
                    Barang</button>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Jumlah</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="item in data_barang">
                                <tr>
                                    <td>@{{ item . nama }}</td>
                                    <td>@{{ item . jumlah }}</td>
                                    <td>@{{ item . created_at }}</td>
                                    <td>@{{ item . updated_at }}</td>
                                    <td>
                                        <button @click.prevent="editData(item.id)"
                                            class="btn btn-xs btn-warning">Edit</button>
                                        <button @click.prevent="hapusData(item.id)"
                                            class="btn btn-xs btn-danger">Hapus</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <div>
                    <button v-if="prev_page_url" v-on:click.prevent="gantiHalaman(prev_page_url)"
                        class="btn btn-primary">Prev</button>
                    <button v-if="next_page_url" v-on:click.prevent="gantiHalaman(next_page_url)"
                        class="btn btn-primary">Next</button>
                </div>

                <div class="row mt-3 mb-2">
                    <div class="col-md-1">
                        <select class="form-control" v-model="paging" v-on:change="getData()">
                            <option value="1">1</option>
                            <option value="3">3</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                        </select>
                    </div>
                </div>
                Showing @{{ from }} to @{{ to }} of @{{ total }} entries.
                <br> <br> <br>
            </div>
        </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
    var vue = new Vue({
        el: "#appVue",
        data: {
            url: '',
            data_barang: [],

            paging: '',

            search: '',

            from: '',
            to: '',
            total: '',

            nama: null,
            jumlah: null,
            id_edit: null,

            next_page_url: '',
            prev_page_url: ''
        },
        mounted() {
            this.paging = 5;
            this.url = "{{ url('get-master-barang-paging') }}";
            this.getData();
        },
        methods: {
            getData: function() {
                var url = "{{ url('get-barang') }}";
                axios.get(this.url, {
                        params: {
                            paging: this.paging,
                            search: this.search,
                        }
                    })
                    .then(res => {
                        console.log(res);
                        this.data_barang = res.data.data;

                        this.next_page_url = res.data.next_page_url;
                        this.prev_page_url = res.data.prev_page_url;

                        this.from = res.data.from;
                        this.to = res.data.to;
                        this.total = res.data.total;
                    })
                    .catch(err => {
                        console.log(err);
                    })
            },
            gantiHalaman: function(url) {
                this.url = url;
                this.getData();
            },
            tambahData: function() {
                $('#modalTambahData').modal('show');
            },
            closeModal: function() {
                $('#modalTambahData').modal('hide');
            },
            storeBarang: function() {
                var form_data = new FormData();
                form_data.append("nama", this.nama);
                form_data.append("jumlah", this.jumlah);
                form_data.append("id_edit", this.id_edit);

                var url = "{{ url('store-barang') }}";
                axios.post(url, form_data)
                    .then(resp => {
                        $('#modalTambahData').modal('hide');
                        alert('sukses');
                        this.nama = null;
                        this.jumlah = null;
                        this.id_edit = null;

                        this.getData();
                    })
                    .catch(err => {
                        alert('error');
                        console.log(err);
                    })
            },
            editData: function(id) {
                this.id_edit = id;
                var url = "{{ url('get-barang') }}" + '/' + id;
                axios.put(url)
                    .then(resp => {
                        var barang = resp.data;
                        this.nama = barang.nama;
                        this.jumlah = barang.jumlah;
                        this.tambahData();
                    })
                    .catch(err => {
                        alert('error');
                        console.log(err);
                    })
            },
            hapusData: function(id) {
                var url = "{{ url('hapus-barang') }}" + '/' + id;
                axios.delete(url)
                    .then(resp => {
                        console.log(resp);
                        this.getData();
                    })
                    .catch(err => {
                        alert('error');
                        console.log(err);
                    })
            },
            gantiHalaman: function(url) {
                this.url = url;
                this.getData();
            }
        }
    })
    </script>
</body>

</html>