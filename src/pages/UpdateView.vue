<template>
  <div class="container">
    <div class="card">
      <div class="card-header">
        Todo Update
      </div>
      <div class="card-body">
        <form @submit.prevent="onSubmit"> 
          <div class="form-group">            
            <label for="title">제목</label>
            <input type="text" v-model="todo.title" class="form-control" name="title" id="title" aria-describedby="helpId" placeholder="">
            <small id="helpId" class="form-text text-muted" :class="!todo.title ? 'red' : '' ">제목을 입력하세요.</small>
          </div>
          <div class="form-group">
            <label for="body">내용</label>
            <textarea v-model="todo.body" class="form-control" name="body" id="body" rows="3"></textarea>
            <small id="helpId" class="form-text text-muted" :class="!todo.body ? 'red' : '' ">내용을 입력하세요</small>
          </div>

          <div class="from-group">
            <label>완료여부</label>
            <input type="checkbox" class="ml-2 mr-2" :id="todo.id" v-model="todo.active" @change="toggleTodo()">
            <span class="form-check-label" :class="todo.active == false ? 'active' : '' ">
                  {{ todo.active ? "완료" : "진행중" }}
                </span>
          </div>

          <div class="btn-group" role="group">
            <button type="submit" class="btn btn-danger" :disabled="todoUpdate">수정</button>
            <button type="button" class="btn btn-primary" @click="moveList">목록보기</button>
          </div>
          
        </form>
      </div>
      
    </div>
    
      
  </div>
</template>

<script>
  import {reactive, computed } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import _ from 'lodash'
  import {useToast} from '@/composables/toast.js'



export default {
  setup() {
    const route = useRoute();

    const todo = reactive({
      title : '',
      body : '',
      id : 0,
      complete : false
    });
    // 원본값
    const originalTodo = reactive({      
      title : '',
      body : '',
      id : 0,
      complete : false
    });

    // todo와 originalTodo를 비교
    // 기 결과가 true or false 인지 비교
    const todoUpdate = computed(()=> {
      return _.isEqual(todo, originalTodo);
    });

    
    // 안내문
    const {
        toastMessage,
        toastShow,
        triggerToast
    } = useToast();


    // console.log(route.params.id);
    // id를 전달하고 자료를 받아온다
    const getInfo = () => {
      fetch(`http://paragon.dothome.co.kr/data_read_id.php?id=${route.params.id}`)
      .then( res => res.json() )
      .then( data => {
        // 현재 화면에 보일값 갱신되는 값 보관용
        todo.title = data.result[0].title;
        todo.body = data.result[0].body;
        todo.complete = data.result[0].complete;
        todo.id = data.result[0].id;
        if(todo.complete === '0') {
          todo.active = false;
        } else {
          todo.active = true;
        }

        // 원본 보관
        originalTodo.title = data.result[0].title;
        originalTodo.body = data.result[0].body;
        originalTodo.complete = data.result[0].complete;
        originalTodo.id = data.result[0].id;
        if(originalTodo.complete === '0') {
          originalTodo.active = false;
        } else {
          originalTodo.active = true;
        }

        
      })
      .catch()
    }

    getInfo();

    const toggleTodo = () => {
      if(todo.active == true) {
        todo.complete = '1';
      } else {
        todo.complete = '0';
      }      
    }

    const onSubmit = () => {

      if(!todo.title) {
        triggerToast('제목을 입력하세요.');
        return;
      }
      // 내용 없을 시 중지
      if(!todo.body) {
        triggerToast('내용을 입력하세요.');
        return;
      }

      fetch(`http://paragon.dothome.co.kr/data_update.php?id=${todo.id}&title=${todo.title}&body=${todo.body}&complete=${todo.complete}`)
      .then(res => res.json() )
      .then( data => {
        // 목록갱신
        if(data.result == 1) {
          // list 화면으로 이동          
          moveDetail(todo.id)
        } else {
          console.log('서버에서 자료가 오지 않았어요');
        }
      })
      .catch()
    }

    const router = useRouter();
    const moveDetail = (_id) => {
      router.push({
        name : 'Detail',
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
      onSubmit,
      moveList,
      
      toastMessage,
      toastShow,

      todoUpdate,
      toggleTodo
    }
  }

}
</script>

<style>
.red {
  color : red !important;
}

</style>