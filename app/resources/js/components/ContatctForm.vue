<style scoped>
    .error {
        border-color: #e94b35 !important;
    }
    .message {
        height: 200px;
    }
    button .submit {
        border: none;
        background: #2c3e50;
        border-radius: 0.25em;
        padding: 12px 20px;
        color: #ffffff;
        font-weight: bold;
        float: right;
        cursor: pointer;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        appearance: none;
    }
    .no-touch button .submit:hover {
        background: #42a2e1;
    }
    button .submit:focus {
        outline: none;
        background: #2b3e51;
    }
    button .submit:active {
        transform: scale(0.9);
    }
</style>
<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Contact Us</div>

                    <div class="card-body">
                        <form role="form">
                            <!-- Form Errors -->
                            <div class="alert alert-danger" v-if="formData.errors.length > 0">
                                <p class="mb-0"><strong>Whoops!</strong> Something went wrong!</p>
                                <br>
                                <ul>
                                    <li v-for="error in formData.errors">
                                        {{ error }}
                                    </li>
                                </ul>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Name</label>

                                <div class="col-md-9">
                                    <input  type="text" class="form-control"
                                            @keyup.enter="store" v-model="formData.name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Email</label>
                                <div class="col-md-9">
                                    <input type="email" name="email" id="email" required=""
                                           :class="['form-control', {error: !email_valid }]"
                                           v-model="formData.email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Description</label>

                                <div class="col-md-9">
                                    <input  type="text" class="form-control"
                                            @keyup.enter="store" v-model="formData.description">

                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Message</label>
                                <div class="col-md-9">
                                    <textarea class="message form-control" name="textarea" id="textarea" required=""
                                          v-model="formData.message"
                                          :maxlength="message_maxlength"></textarea>
                                    <span class="counter">{{ formData.message.length }} / {{ message_maxlength }} </span>
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary submit" @click="store">
                                    Send Form
                                </button>
                            </div>

                        </form>
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
               emailRegExp: /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,

               formData: {
                   name: '',
                   message : '',
                   email: '',
                   description: '',
                   errors: []
               },
               message_maxlength: 2000,
               email_valid: true,
           }
        },
        mounted() {
            this.prepareComponent();
        },
        watch: {
            // watching nested property
            "formData.email": function(value) {
                this.validate("email", value);
            }
        },
        methods : {
            prepareComponent(){

            },
            validate: function(type, value) {
                if (type === "email") {
                    this.email_valid = this.isEmail(value) ? true : false;
                }
            },
            // check for valid email adress
            isEmail: function(value) {
                return this.emailRegExp.test(value);
            },
            checkForm: function () {
                this.formData.errors = [];
                if (!this.formData.name) {
                    this.formData.errors.push('Name required.');
                }

                if (!this.formData.email) {
                    this.formData.errors.push('Email required.');
                } else if(!this.isEmail(this.formData.email)) {
                    this.formData.errors.push('Email invalid.');
                }

                if (!this.formData.message) {
                    this.formData.errors.push('Message required.');
                }
                return this.formData.errors.length === 0;
            },
            store() {
                if(this.checkForm()) {
                    this.persist('post','contact/send',this.formData);
                }
            },
            cleanData() {
                this.formData.message = '';
                this.formData.name = '';
                this.formData.email = '';
                this.formData.description = '';

            },
            /**
             * Persist the client to storage using the given form.
             */
            persist(method, uri, form, modal) {
                this.isLoading = true;
                form.errors = [];



                axios[method](uri, form)
                    .then(response => {
                        this.cleanData();

                        form.errors = [];
                        this.$toast.success('Your message was sent. Thanks!');
                        window.location = "/";


                    })
                    .catch(error => {
                        this.$toast.error('Something went wrong! Please try again later!');
                        if (typeof error.response.data === 'object') {
                            form.errors = _.flatten(_.toArray(error.response.data.errors));
                        } else {
                            form.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },
        }
    }
</script>
