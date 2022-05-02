<template>
  <div class="container">
    <div class="card">
      <div class="card-header">
        Todo Create
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

          <div class="btn-group" role="group">
            <button type="submit" class="btn btn-success">확인</button>
            <button type="button" class="btn btn-warning" @click="moveList">취소</button>
          </div>

        </form>
      </div>
      
    </div>

      <!-- 안내창 -->
      <ToastBox :message="toastMessage" v-if="toastShow" />
  </div>
</template>

<script>
  import {reactive, ref, onUnmounted} from 'vue'
  import {useRouter} from 'vue-router'
  import ToastBox from '@/components/ToastBox.vue'
export default {
  components : {
    ToastBox
  },
  
  setup() {
    const router = useRouter();
    const todo = reactive({
      title : '',
      body : ''
    });

    const toastMessage = ref('');
    const toastShow = ref(false);
    // 타이머로 제어
    const toastId = ref(null);

    const triggerToast = (_m) => {
      toastMessage.value = _m;
      toastShow.value = true;
      toastId.value = setTimeout(() => {
        toastShow.value = false;
        toastMessage.value = '';
        clearTimeout(toastId.value);
      }, 2000);
    }

    onUnmounted (()=> {      
      clearTimeout(toastId.value);
    })

    const onSubmit = () => {
      // 제목 없을 시 중지
      if(!todo.title) {
        triggerToast('제목을 입력하세요.');
        return;
      }
      // 내용 없을 시 중지
      if(!todo.body) {
        triggerToast('내용을 입력하세요.');
        return;
      }


      console.log(todo);
      fetch(`http://paragon.dothome.co.kr/data_add.php?title=${todo.title}&body=${todo.body}`)
      .then(res => res.json()) // 받은 것을 json으로 처리
      .then(data => {
        if(data.result == 1) {
          // list 화면으로 이동
          router.push({
            name : 'List'
          });
        } else {
          console.log('서버에서 자료가 오지 않았어요');
        }
      }) // 만든 데이터 어떻게 처리할지
      .catch() // 에러
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
      toastShow
    }
  }

}
</script>

<style>
.red {
  color : red !important;
}

</style>