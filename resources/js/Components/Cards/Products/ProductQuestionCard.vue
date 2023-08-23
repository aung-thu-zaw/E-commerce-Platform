<script setup>
import AnswerForm from "@/Components/Forms/AnswerForm.vue";
import QuestionEditForm from "@/Components/Forms/QuestionEditForm.vue";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
  product: Object,
  question: Object,
});

const isEditQuestionFormVisible = ref(false);
const isAnswerFormVisible = ref(false);

const handleDeleteQuestion = () => {
  router.post(
    route("product.question.destroy", {
      question_id: props.question.id,
    }),
    {},
    {
      replace: true,
      preserveScroll: true,
    }
  );
};
</script>


<template>
  <div class="relative w-full">
    <div class="flex flex-col items-end w-full">
      <div class="flex items-start justify-between w-full mb-1">
        <div class="flex items-start">
          <img
            :src="question.user.avatar"
            alt=""
            class="z-10 w-10 h-10 object-cover rounded-full mr-5 ring-2 ring-orange-300"
          />
          <div>
            <h4 class="text-lg font-bold text-slate-700">
              {{ question.user.name }}
            </h4>
            <p class="text-[.8rem] text-slate-500">Question From User</p>
          </div>
        </div>

        <div class="flex items-center">
          <span class="text-slate-500 text-sm font-bold">
            {{ question.updated_at }}
          </span>
          <button
            :id="'dropdownMenuIconButton' + question.id"
            :data-dropdown-toggle="'dropdownDot' + question.id"
            class="inline-flex items-center text-sm text-center text-gray-700 bg-white focus:outline-none ml-5"
            type="button"
          >
            <svg
              class="w-5 h-5"
              aria-hidden="true"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"
              ></path>
            </svg>
          </button>
        </div>

        <div
          :id="'dropdownDot' + question.id"
          class="z-40 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600"
        >
          <ul
            class="py-2 text-sm text-gray-700 dark:text-gray-200"
            :aria-labelledby="'dropdownMenuIconButton' + question.id"
          >
            <li>
              <button
                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white w-full text-left"
              >
                <i class="fa-solid fa-eye-slash"></i>
                Hide Question
              </button>
            </li>

            <li>
              <button
                v-if="
                  $page.props.auth.user &&
                  (question.user_id === $page.props.auth.user.id ||
                    question.product.user_id === $page.props.auth.user.id)
                "
                @click="handleDeleteQuestion"
                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white w-full text-left"
              >
                <i class="fa-solid fa-trash"></i>
                Delete Question
              </button>
            </li>
          </ul>
        </div>
      </div>
      <p class="w-[93%] text-sm font-normal text-slate-900 ml-auto mb-3">
        {{ question.question_text }}
      </p>
    </div>

    <div
      v-if="
        !question.product_answer &&
        $page.props.auth.user &&
        product.user_id == $page.props.auth.user.id
      "
      class="my-3 flex items-center justify-end w-full"
    >
      <button
        @click="isAnswerFormVisible = !isAnswerFormVisible"
        class="font-bold border text-[.7rem] text-sky-700 px-3 py-2 rounded-sm border-sky-700 hover:bg-sky-700 hover:text-white transition-all"
      >
        <i class="fa-solid fa-flag"></i>
        Answer This Question
      </button>
    </div>
    <div
      v-if="
        $page.props.auth.user && $page.props.auth.user.id === question.user_id
      "
      class="mb-3 flex items-center justify-end w-full"
    >
      <button
        @click="isEditQuestionFormVisible = !isEditQuestionFormVisible"
        class="font-bold border text-[.7rem] text-sky-700 px-3 py-2 rounded-sm border-sky-700 hover:bg-sky-700 hover:text-white transition-all"
      >
        <i class="fa-solid fa-flag"></i>
        Edit Question
      </button>
    </div>

    <div v-if="isEditQuestionFormVisible" class="w-full">
      <QuestionEditForm
        :question="question"
        :product="product"
        @isVisible="isEditQuestionFormVisible = false"
      />
    </div>

    <div v-if="isAnswerFormVisible" class="w-full">
      <AnswerForm
        :product="product"
        :question="question"
        @isVisible="isAnswerFormVisible = false"
      />
    </div>

    <hr v-if="question.product_answer" class="mt-5" />
  </div>
</template>