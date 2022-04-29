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

  </div>
</template>

<script>

export default {
}
</script>

<style>
#app {
}
</style>
