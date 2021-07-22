<template>
  <div class="container">
    <div class="row justify-content-center">
      <form>
        <div class="form-group">
          <label for="exampleFormControlInput1">Porcentaje</label>
          <input
            type="number"
            class="form-control"
            id="exampleFormControlInput1"
            placeholder=""
          />
        </div>

        <div class="form-group">
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              value=""
              id="defaultCheck1"
            />
            <label class="form-check-label" for="defaultCheck1">
              Por defecto
            </label>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
