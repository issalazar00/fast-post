<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6">
        <h2 class="text-center py-2">¡INICIAR SESION!</h2>
        <div class="row">
          <div class="d-none d-md-block col-6 text-center">
            <img class="img-fluid" src="https://picsum.photos/200/300" alt="" />
          </div>
          <div class="col-6">
            <form id="form_login" autocomplete="off" @submit.prevent="login">
              <div class="form-group">
                <label for="exampleInputEmail1">Email</label>
                <input
                  type="email"
                  class="form-control"
                  id="email"
                  aria-describedby="emailHelp"
                  name="email"
                  placeholder="Ingresar email"
                  required
                  v-model="formValues.email"
                />
                <small id="emailHelp" class="form-text text-danger">{{
                  formErrors.email
                }}</small>
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input
                  type="password"
                  class="form-control"
                  id="password"
                  aria-describedby="passwordHelp"
                  name="password"
                  placeholder="Ingresar contraseña"
                  required
                  v-model="formValues.password"
                />
                <small id="passwordHelp" class="form-text text-danger">{{
                  formErrors.password
                }}</small>
              </div>
              <button type="submit" class="btn btn-primary">Acceder</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
//Services
import global from "./../services/global.js";

export default {
  name: "Login",
  data() {
    return {
      data: "Login",
      api: global.api,
      formValues: {
        email: "",
        password: "",
      },
      formErrors: {
        email: "",
        password: "",
      },
    };
  },
  created() {},
  methods: {
    login() {
      let config = {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      };

      this.formErrors.email = "";
      this.formErrors.password = "";

      const formLogin = document.getElementById("form_login");
      axios
        .post(this.api + "/login", new FormData(formLogin), config)
        .then((response) => {
          response = response.data;
          if (
            response.status == "success" &&
            typeof response.user === "object"
          ) {
            localStorage.setItem("token", response.user.api_token);
            localStorage.setItem("user", JSON.stringify(response.user));
            this.$router.push("/");
          }
        })
        .catch((error) => {
          var errors = error.response.data.errors;

          if (typeof errors != "undefined") {
            if (typeof errors.email != "undefined") {
              this.formValues.email = "";
              this.formValues.password = "";

              this.formErrors.email = errors.email[0];
            }

            this.formValues.password = "";
          } else {
            this.formValues.password = "";
            this.formErrors.password = error.response.data.message;
          }

          console.log(error.response);
        });
    },
  },
};
</script>
