<template>
  <div class="container">
    <header class="d-flex justify-content-center py-3">
      
      <ul class="nav nav-pills">
        <li class="nav-item">
          <router-link :to="{name:'Home'}" class="nav-link">Home</router-link> 

        </li>
        <li class="nav-item">
        <router-link :to="{name : 'Create'}" class="nav-link">Create</router-link> 
        </li>
        <li class="nav-item">
        <router-link :to="{name : 'List'}" class="nav-link">List</router-link> 
        </li>
        <li class="nav-item">
        <router-link :to="{name : 'About'}" class="nav-link">About</router-link> 
        </li>

      </ul>
      
    </header>
    <router-view/>

  <!-- 안내창 -->
  <transition name="fade">
      <ToastBox :message="toastMessage" v-if="toastShow" />

  </transition>
  </div>
</template>

<script>
  import ToastBox from '@/components/ToastBox.vue'
  import { useToast } from '@/composables/toast.js'

export default {
  components : {
    ToastBox
  },
  setup(){
    // 안내문
    const {
        toastMessage,
        toastShow,
        triggerToast
    } = useToast();

    return {
      toastMessage,
      toastShow,
      triggerToast

    }
  }
}
</script>

<style>
  #app {}

  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.5s;
  }

.fade-enter-from,
.fade-leave-to {
  opacity : 0;
}

.fade-enter-to,
.fade-leave-from {
  opacity : 1;

}

</style>
