<template>
  <div class="card">
    <div class="card-header">
      Todo
    </div>
    <div class="card-body">
      <h4 class="card-title">{{todo.title}}</h4>
      <p class="card-text">{{todo.body}}</p>
      <p class="card-text">{{todo.complete}}</p>
      <p class="card-text">{{todo.id}}</p>
      <div class="btn-group" role="group">
        <button type="button" class="btn btn-warning" @click="editTodo(todo.id)">수정</button>
        <button type="button" class="btn btn-danger" @click="deleteTodo(todo.id)">삭제</button>
        <button type="button" class="btn btn-primary" @click="moveList">목록보기</button>
      </div>
  </div>
  </div>
</template>

<script>
import { reactive } from 'vue';
import { useRoute, useRouter } from 'vue-router'

export default {
  setup() {
    const route = useRoute();

    const todo = reactive({
      title : '',
      body : '',
      id : 0,
      complete : 0
    });
    // console.log(route.params.id);
    // id를 전달하고 자료를 받아온다
    const getInfo = () => {
      fetch(`http://paragon.dothome.co.kr/data_read_id.php?id=${route.params.id}`)
      .then( res => res.json() )
      .then( data => {
        todo.title = data.result[0].title;
        todo.body = data.result[0].body;
        todo.complete = data.result[0].complete;
        todo.id = data.result[0].id;
      })
      .catch()
    }

    getInfo();

    const deleteTodo = (_id) => {
      fetch(`http://paragon.dothome.co.kr/data_delete.php?id=${_id}`)
      .then(res => res.json() )
      .then( data => {
        console.log(data);
        // 목록갱신
        if(data.result == 1) {
          // list 화면으로 이동
          router.push({
            name : 'List'
          });
        } else {
          console.log('서버에서 자료가 오지 않았어요');
        }
      })
      .catch()
    }


    const router = useRouter();
    const editTodo = (_id) => {
      router.push({
        name : 'Update',
        params : {
          id : _id
        }
      });
    }

    const moveList = () => {
      router.push({
        name : 'List'
      })
    }

    
    
    return {
      todo,
      deleteTodo,
      editTodo,
      moveList
    }
  }
}
</script>

<style>

</style>