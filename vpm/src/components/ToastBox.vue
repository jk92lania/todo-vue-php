<template>
  <div class="alert alert-danger toast-box">
      {{message}}
  </div>
</template>

<script>
export default {
    props : {
        message : {
            type : String,
            requierd : true
        }
    }
}
</script>

<style>
.toast-box {
    position: fixed;
    top: 10px;
    right:  10px;
    z-index: 9;
}
</style>