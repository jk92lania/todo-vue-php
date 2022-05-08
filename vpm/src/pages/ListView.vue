<template>
  <div class="container">
    <div class="card">
      <div class="card-header">
        Todo List
        <button class="btn btn-success bt-write" @click="writeTodo">글쓰기</button>
        
        <!-- search -->
        <div class="input-group mr-2 search">
          <input type="form-control" placeholder="검색" v-model="searchTxt" @keyup.enter="getTotalSearch()">
        </div>

      </div>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Title</th>
              <th>
                Complete
                <input type="checkbox" class="ml-2 mr-2" v-model="allComplete" @change="toggleAllComplete()">
              </th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in todos" :key="item.id">
              <td>{{index + 1}}</td>
              <td><span @click="moveDetail(item.id)" class="detail">{{item.title}}</span></td>
              <td>
                <input type="checkbox" class="ml-2 mr-2" :id="item.id" v-model="item.active" @change="toggleTodo(item)">
                <span class="form-check-label" :class="item.active == false? 'active' : '' ">
                  {{item.active ? "완료" : "진행중"}}
                </span>
              </td>
              <td>
                <div class="btn-group" role="group">
                  <button class="btn btn-primary" @click="editTodo(item.id)">수정</button>
                  <button class="btn btn-danger" @click="openModal(item.id)">삭제</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <nav aria-label="Page navigation example" v-show="page_total > 1">
      <ul class="pagination">
        <li class="page-item" v-show="page_now != 1">
          <a class="page-link" href="#" @click="getInfo(page_now - 1)">Previous</a>
        </li>

        <li class="page-item" v-for="item in page_total" :key="item">
          <a class="page-link" href="#" @click="getInfo(item)" :class="page_now == item ? 'active' : '' ">{{item}}</a>
        </li>
        
        <li class="page-item" v-show="page_now != page_total">
          <a class="page-link" href="#" @click="getInfo(page_now + 1)">Next</a>
          </li>
      </ul>
    </nav>

    <teleport to="#popup">
      <ModalWin v-if="showModal" @close="closeModal" @delete="deleteTodo">
        <template v-slot:title>할일 삭제</template>
        <template v-slot:body>정말 삭제하시겠습니까?</template>
      </ModalWin>

    </teleport>
  </div>

</template>

<script>
import {ref, watch} from 'vue';
import {useRouter} from 'vue-router';
import ModalWin from '@/components/ModalWin.vue';

export default {
  components : {
    ModalWin
  },
  setup() {    
    // 자료 보관 배열
    const todos = ref([]);
    
    const allTrue = ref(0);
    const allComplete = ref(false);

    // 서버 자료 읽어오기
    const getInfo = (_page = 1) => {
      page_now.value = _page;
      if(searchActive.value == true) {
        // 검색중
        searchTodo();
        return;
      }
      fetch(`http://paragon.dothome.co.kr/data_read.php?page_now=${page_now.value}&data_count=${data_count.value}`)
      .then(res => res.json())
      .then(data => {
        todos.value = data.result;
        // todos의 종류는 배열이다.
        // 배열의 for를 이용 접근해서 만약에 각 배열의 complete 가 0, 1이냐 따라서
        // 그 값을 반영하는 객체를 추가한다.
        allTrue.value = 0;
        allComplete.value = false;
        for(let item of todos.value) {
          if(item.complete === '0') {
            item.active = false;
          } else {
            item.active = true;
            allTrue.value++;
          }
        }
        changeAllComplete();
      })
      .catch()
    }

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
        if(data.result == 1) {
          showModal.value = false;
          deleteId.value = null;  
          allTrue.value--;   
          // 목록갱신
          // getInfo();          
          getTotal();
        } else {
          console.log('삭제 실패')
        }

      })
      .catch()
    }
    
    const toggleAllComplete = () => {
      if(allComplete.value == false) {
        allTrue.value = todos.value.length;
        console.log('true -> false');
        for(let item of todos.value) {
          item.active = false;
            toggleTodo(item);
        }
      } else {
        allTrue.value = 0;
        console.log('false -> true');
        for(let item of todos.value) {
          item.active = true;
            toggleTodo(item);
        }
      }
      changeAllComplete();
    }

    const changeAllComplete = () => {
      if(allTrue.value == todos.value.length) {
        allComplete.value = true;
      } else {
        allComplete.value = false;
      }
    }

    const toggleTodo = (_obj) => {
      if(_obj.active == true) {
        _obj.complete = '1';
        allTrue.value++;
      } else {
        _obj.complete = '0';
        allTrue.value--;
      }
      fetch(`http://paragon.dothome.co.kr/data_update.php?id=${_obj.id}&title=${_obj.title}&body=${_obj.body}&complete=${_obj.complete}`)
      .then(res => res.json())
      .then(data => {
        if(data.result == 1) {
          // console.log(allTrue.value);
          changeAllComplete();  
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

    const editTodo = (_id) => {
      router.push({
        name : 'Update',
        params : {
          id : _id
        }
      });
    }

    const writeTodo = () => {
      router.push({
        name : 'Create'
      })
    }

    // pagination 직접 구현
    // 전체데이터 개수
    const data_total = ref(0);
    // 페이지당 보여줄 개수
    const data_count = ref(5);
      data_count.value = 5;
    // 총페이지 수
    const page_total = ref(0);
    // 현재 페이지
    const page_now = ref(1);


    const getTotal = () => {
      fetch(`http://paragon.dothome.co.kr/data_total.php`)
      .then(res => res.json() )
      .then(data => {
        data_total.value = data.total;
        page_total.value = Math.ceil(data_total.value / data_count.value);
        page_now.value = 1;        
        getInfo();
      })
      .catch();
    }
    getTotal();

    // 검색 기능
    // 현재 생성된 목록이 검색으로 된 것인지
    // 아니면 검색없이 일반적인 목록인지를 구분하는 변수
    const searchActive = ref(false);
    // 검색어
    const searchTxt = ref('');
    // 잦은 검색 php 요청을 한느 것을 방지
    // 일정 시간 딜레이를 줘서 php 부하를 줄여주는 용도
    let searchTimer = null;
    // searchTxt가 변하는 것을 감시
    watch(searchTxt, () => {
      clearTimeout(searchTimer);
      if(searchTxt.value !== '') {
        searchActive.value = true;
        searchTimer =  setTimeout(() => {
          // 검색어와 동일한 내용을 php를 이용해서
          // 전체 개수를 파악(data_total_search.php)
        getTotalSearch();
        }, 2000);
      } else {
        searchActive.value = false;
        getTotal();
      }
    });

    const getTotalSearch = () => {
      clearTimeout(searchTimer);
      fetch(`http://paragon.dothome.co.kr/data_total_search.php?title=${searchTxt.value}`)
      .then(res => res.json() )
      .then(data => {
        data_total.value = data.total;
        //
        page_total.value = Math.ceil(data_total.value / data_count.value);
        // 시작 페이지는 1페이지로 셋팅
        page_now.value = 1;
        // 실제 내용 가져오기 실행
        searchTodo();
      })
      .catch()

      // 검색에 해당되는 내용을 목록으로 가지고 오는 php 실행
      // data_read_search.php
      
    }

      const searchTodo = () => {
        fetch(`http://paragon.dothome.co.kr/data_read_search.php?page_now=${page_now.value}&data_count=${data_count.value}&title=${searchTxt.value}`)
        .then(res => res.json() )
        .then(data => {
          // 검색된 배열을 받아서 todos를 업뎃
          todos.value = data.result;
          // complete 를 '0'
          
          allTrue.value = 0;
          allComplete.value = false;
          for(let item of todos.value) {
            if(item.complete === '0') {
              item.active = false;
            } else {
              item.active = true;
              allTrue.value++;
            }
          }      
          changeAllComplete();
        })
        .catch()
      }



    return {
      todos,
      deleteTodo,
      moveDetail,
      editTodo,
      writeTodo,
      page_total,
      getInfo,
      page_now,

      closeModal,
      openModal,
      showModal,

      toggleTodo,
      toggleAllComplete,
      allComplete,

      searchTxt,
      getTotalSearch

    }
  }
}
</script>

<style>
.detail {
  text-decoration:  underline;
  color : #000;
  cursor: pointer;

}
.detail:hover {
  color: hotpink;
}
.bt-write {
  float: right;
}

.active {
  background-color: palevioletred;
  color: aliceblue;
}

.search {
  width: 50%;
  float: right;
}

</style>