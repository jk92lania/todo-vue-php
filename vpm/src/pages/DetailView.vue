<template>
  <div class="card">
    <div class="card-header">
      Todo
    </div>
    <div class="card-body">
      <h4 class="card-title">{{todo.title}}</h4>
      <p class="card-text">
          {{todo.body}}
        </p>
      <p class="card-text">{{todo.complete === '0' ? "진행중" : "완료"}}</p>
      <div class="btn-group" role="group">
        <button type="button" class="btn btn-warning" @click="editTodo(todo.id)">수정</button>
        <button type="button" class="btn btn-danger" @click="openModal(todo.id)">삭제</button>
        <button type="button" class="btn btn-primary" @click="moveList">목록보기</button>
      </div>
  </div>
  <teleport to="#popup">
      <ModalWin v-if="showModal" @close="closeModal" @delete="deleteTodo">
        <template v-slot:title>할일 삭제</template>
        <template v-slot:body>정말 삭제하시겠습니까?</template>
      </ModalWin>

    </teleport>
  </div>
</template>

<script>
import { reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import ModalWin from '@/components/ModalWin.vue';

export default {
  components : {
    ModalWin
  },
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

        if(data.result[0].complete == 0) {
            todo.active = false;
          } else {
            todo.active = true;
          }
      })
      .catch()
    }

    getInfo();

    // modal 보일지 말지 
    const showModal = ref(false);
    // 선택된 id를 저장
    const deleteId = ref(null);

    const closeModal = () => {
      showModal.value = false;
      deleteId.value = null;
    }

    const openModal = (_id) => {
      deleteId.value = _id;
      showModal.value = true;
    }

    const deleteTodo = () => {
      fetch(`http://paragon.dothome.co.kr/data_delete.php?id=${deleteId.value}`)
      .then(res => res.json() )
      .then( data => {
        console.log(data);
        // 목록갱신
        if(data.result == 1) {
          // list 화면으로 이동          
          showModal.value = false;
          deleteId.value = null;
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
      moveList,

      closeModal,
      openModal,
      showModal
    }
  }
}
</script>

<style>

</style>