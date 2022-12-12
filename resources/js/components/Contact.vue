<template>
    <div>
        <div class="container col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="">
                        <h2>Agenda de contactos</h2>
                        <!-- Button trigger modal -->
                        <div class="text-sm-end">
                            <button @click="update = false; openModal();" type="button" class="btn btn-primary">
                                Crear nuevo contacto
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Content here -->
                <div class="card-body">
                    <table class="table table-striped table-hover table-responsive">
                        <thead>
                        <tr>
                            <th scope="col">nombre</th>
                            <th scope="col">teléfono</th>
                            <th scope="col">edad</th>
                            <th scope="col">fecha de cumpleaños</th>
                            <th scope="col">dirección</th>
                            <th scope="col">correo electrónico</th>
                            <th scope="col" colspan="2">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="contact in contacts.data" :key="contacts.id">
                            <td>{{ contact.name }}</td>
                            <td>{{ contact.phone }}</td>
                            <td>{{ CalcularEdad(contact.birthdate)}}</td>
                            <td>{{ contact.birthdate }}</td>
                            <td>{{ contact.address }}</td>
                            <td>{{ contact.email }}</td>
                            <td>
                                <button @click="update=true; openModal(contact);" class="btn btn-warning btn-sm">Editar</button>
                            </td>
                            <td>
                                <button @click="deleted(contact.id);" class="btn btn-danger btn-sm">Eliminar</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <!-- Pagination -->
                    <pagination class="justify-content-center" :data="contacts" @pagination-change-page="list"></pagination>
                    <!-- Modal -->
                    <div class="modal fade" :class="{show:modal}">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ titleModal }}</h5>
                                    <button @click="closeModal();" type="button" class="btn-close"
                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nombre</label>
                                            <input v-model="contact.name" type="text" class="form-control" id="name"
                                                   aria-describedby="nameHelp">
                                            <div id="nameHelp" class="form-text">Ingrese el nombre del contacto.</div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Teléfono</label>
                                            <input v-model="contact.phone" type="tel" class="form-control" id="phone"
                                                   aria-describedby="phoneHelp">
                                            <div id="phoneHelp" class="form-text">Ingrese el teléfono del contacto.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="birthdate" class="form-label">Fecha de nacimiento</label>
                                            <input v-model="contact.birthdate" type="date" class="form-control"
                                                   id="name" aria-describedby="birthdateHelp">
                                            <div id="birthdateHelp" class="form-text">Seleccione la fecha de nacimiento
                                                del contacto.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Dirección</label>
                                            <input v-model="contact.address" type="text" class="form-control"
                                                   id="address" aria-describedby="addressHelp">
                                            <div id="addressHelp" class="form-text">Ingrese la dirección del contacto.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Dirección de correo</label>
                                            <input v-model="contact.email" type="email" class="form-control" id="email"
                                                   aria-describedby="emailHelp">
                                            <div id="emailHelp" class="form-text">Ingrese el email del contacto.</div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button @click="closeModal();" type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close
                                    </button>
                                    <button @click="save();" type="button" class="btn btn-success">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            contact: {
                name: '',
                phone: '',
                birthdate: '',
                address: '',
                email: '',
            },
            id: 0,
            update: true,
            modal: 0,
            titleModal: '',
            contacts: {}
        }
    },
    methods: {
        list(page = 1) {
            axios.get('contacts?page=' + page)
                .then(res => {
                    this.contacts = res.data;
                });
        },
        /*async list() {
            const res = await axios.get('contacts');
            this.contacts = res.data;
        },*/
        async deleted(id) {
            await axios.delete('/contacts/' + id);
            this.$swal({
                    icon: 'success',
                    title: 'Contacto eliminado'
            });
            await this.list();
        },
        async save() {
            if (this.update) {
                const res = await axios.put('/contacts/' + this.id, this.contact);
                this.$swal({
                    icon: 'success',
                    title: 'Contacto actualizado'
                });
                console.log(res);
            } else {
                const res = await axios.post('/contacts', this.contact);
                this.$swal({
                    icon: 'success',
                    title: 'Contacto creado'
                });
            }
            this.closeModal();
            await this.list();
        },
        openModal(data = {}) {
            this.modal = 1;
            if (this.update) {
                this.titleModal = 'Edición de contacto';
                this.id = data.id;
                this.contact.name = data.name;
                this.contact.phone = data.phone;
                this.contact.birthdate = data.birthdate;
                this.contact.address = data.address;
                this.contact.email = data.email;


            } else {
                this.titleModal = 'Creación de contacto';
                this.id = 0;
                this.contact.name = '';
                this.contact.phone = '';
                this.contact.birthdate = '';
                this.contact.address = '';
                this.contact.email = '';
            }
        },
        closeModal() {
            this.modal = 0;
        },
        CalcularEdad(birthdate){
            let nacimiento = moment(birthdate);
            let hoy = moment();
            let edad = 0;
            if(nacimiento < hoy){
                edad = hoy.diff(nacimiento, 'years');
            }else{
                this.$swal({
                    icon: 'error',
                    title: 'La fecha de nacimiento no puede ser superior a la fecha actual del sistema.'
                });
            }
            return edad;
        }
    },
    created() {
        this.list();

    }
}
</script>

<style scoped>
.show {
    display: list-item;
    opacity: 1;
    background: rgba(44, 38, 75, 0.849);
}
</style>
