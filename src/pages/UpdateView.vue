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
            <small id="helpId" class="form-text text-muted">제목을 입력하세요.</small>
          </div>
          <div class="form-group">
            <label for="body">내용</label>
            <textarea v-model="todo.body" class="form-control" name="body" id="body" rows="3"></textarea>
            <small id="helpId" class="form-text text-muted">내용을 입력하세요</small>
          </div>

          <div class="form-check">
            <label class="form-check-label">
              <input type="checkbox" class="form-check-input" name="complete" id="complete" value="checkedValue" checked>
              완료여부
            </label>
          </div>

          <div class="btn-group" role="group">
            <button type="submit" class="btn btn-danger">수정</button>
            <button type="button" class="btn btn-primary" @click="moveList">목록보기</button>
          </div>
          
        </form>
      </div>
      
    </div>
  </div>
</template>

<script>
  import {reactive} from 'vue'
  import { useRoute, useRouter } from 'vue-router'
export default {
  // 값이 변하는 것을 계속 유지
  setup() {
    const route = useRoute();

    const todo = reactive({
      title : '',
      body : '',
      id : 0,
      complete : false
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

    const onSubmit = () => {
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
      moveList
    }
  }

}
</script>

<style>

</style>