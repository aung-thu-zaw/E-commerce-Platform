<script setup>
import NotAvaliableData from "@/Components/Table/NotAvaliableData.vue";
import Tr from "@/Components/Table/Tr.vue";
import Td from "@/Components/Table/Td.vue";
import HeaderTh from "@/Components/Table/HeaderTh.vue";
import BodyTh from "@/Components/Table/BodyTh.vue";
import TableHeader from "@/Components/Table/TableHeader.vue";
import TableContainer from "@/Components/Table/TableContainer.vue";
import Breadcrumb from "@/Components/Breadcrumbs/WebsiteFeedbackBreadcrumb.vue";
import Pagination from "@/Components/Paginations/Pagination.vue";
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import { inject, reactive, watch, computed } from "vue";
import { router, usePage, Link, Head } from "@inertiajs/vue3";

const props = defineProps({
  trashWebsiteFeedbacks: Object,
});

const swal = inject("$swal");

const feedbackTrashRestore = computed(() => {
  return usePage().props.auth.user.permissions.length
    ? usePage().props.auth.user.permissions.some(
        (permission) => permission.name === "feedback.trash.restore"
      )
    : false;
});

const feedbackTrashDelete = computed(() => {
  return usePage().props.auth.user.permissions.length
    ? usePage().props.auth.user.permissions.some(
        (permission) => permission.name === "feedback.trash.delete"
      )
    : false;
});

const params = reactive({
  search: null,
  page: props.trashWebsiteFeedbacks.current_page
    ? props.trashWebsiteFeedbacks.current_page
    : 1,
  per_page: props.trashWebsiteFeedbacks.per_page
    ? props.trashWebsiteFeedbacks.per_page
    : 10,
  sort: "id",
  direction: "desc",
});

const handleSearchBox = () => {
  params.search = "";
};

watch(
  () => params.search,
  () => {
    router.get(
      route("admin.website-feedbacks.trash"),
      {
        search: params.search,
        per_page: params.per_page,
        sort: params.sort,
        direction: params.direction,
      },
      {
        replace: true,
        preserveState: true,
      }
    );
  }
);

watch(
  () => params.per_page,
  () => {
    router.get(
      route("admin.website-feedbacks.trash"),
      {
        search: params.search,
        page: params.page,
        per_page: params.per_page,
        sort: params.sort,
        direction: params.direction,
      },
      {
        replace: true,
        preserveState: true,
      }
    );
  }
);

const updateSorting = (sort = "id") => {
  params.sort = sort;
  params.direction = params.direction === "asc" ? "desc" : "asc";

  router.get(
    route("admin.website-feedbacks.trash"),
    {
      search: params.search,
      page: params.page,
      per_page: params.per_page,
      sort: params.sort,
      direction: params.direction,
    },
    { replace: true, preserveState: true }
  );
};

const handleRestore = async (trashWebsiteFeedbackId) => {
  const result = await swal({
    icon: "info",
    title: "Are you sure you want to restore this website feedback?",
    showCancelButton: true,
    confirmButtonText: "Yes, restore",
    confirmButtonColor: "#4d9be9",
    timer: 20000,
    timerProgressBar: true,
    reverseButtons: true,
  });

  if (result.isConfirmed) {
    router.post(
      route("admin.website-feedbacks.restore", {
        id: trashWebsiteFeedbackId,
        page: params.page,
        per_page: params.per_page,
      })
    );
    setTimeout(() => {
      swal({
        icon: "success",
        title: usePage().props.flash.successMessage,
      });
    }, 500);
  }
};

const handleDelete = async (trashWebsiteFeedbackId) => {
  const result = await swal({
    icon: "warning",
    title: "Are you sure you want to delete it from the trash?",
    text: "Website Feedback in the trash will be permanetly deleted! You can't get it back.",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it !",
    confirmButtonColor: "#ef4444",
    timer: 20000,
    timerProgressBar: true,
    reverseButtons: true,
  });

  if (result.isConfirmed) {
    router.delete(
      route("admin.website-feedbacks.forceDelete", {
        id: trashWebsiteFeedbackId,
        page: params.page,
        per_page: params.per_page,
      })
    );
    setTimeout(() => {
      swal({
        icon: "success",
        title: usePage().props.flash.successMessage,
      });
    }, 500);
  }
};

const handlePermanentlyDelete = async () => {
  const result = await swal({
    icon: "warning",
    title: "Are you sure you want to delete it from the trash?",
    text: "All website feedbacks in the trash will be permanetly deleted! You can't get it back.",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it !",
    confirmButtonColor: "#ef4444",
    timer: 20000,
    timerProgressBar: true,
    reverseButtons: true,
  });

  if (result.isConfirmed) {
    router.get(
      route("admin.website-feedbacks.permanentlyDelete", {
        page: params.page,
        per_page: params.per_page,
      })
    );
    setTimeout(() => {
      swal({
        icon: "success",
        title: usePage().props.flash.successMessage,
      });
    }, 500);
  }
};
</script>

<template>
  <AdminDashboardLayout>
    <Head title="Trash Feedbacks" />

    <div class="px-4 md:px-10 mx-auto w-full py-32">
      <div class="flex items-center justify-between mb-10">
        <Breadcrumb>
          <li aria-current="page">
            <div class="flex items-center">
              <svg
                aria-hidden="true"
                class="w-6 h-6 text-gray-400"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fill-rule="evenodd"
                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                  clip-rule="evenodd"
                ></path>
              </svg>
              <span
                class="ml-1 font-medium text-gray-500 md:ml-2 dark:text-gray-400"
                >Trash</span
              >
            </div>
          </li>
        </Breadcrumb>

        <div>
          <Link
            as="button"
            :href="route('admin.website-feedbacks.index')"
            class="text-sm px-3 py-2 uppercase font-semibold rounded-md bg-blue-600 text-white hover:bg-blue-500"
          >
            <i class="fa-solid fa-arrow-left"></i>
            Go Back
          </Link>
        </div>
      </div>

      <div class="flex items-center justify-end mb-5">
        <form class="w-[350px] relative">
          <input
            type="text"
            class="rounded-md border-2 border-slate-300 text-sm p-3 w-full"
            placeholder="Search"
            v-model="params.search"
          />

          <i
            v-if="params.search"
            class="fa-solid fa-xmark absolute top-4 right-5 text-slate-600 cursor-pointer"
            @click="handleSearchBox"
          ></i>
        </form>
        <div class="ml-5">
          <select
            class="py-3 w-[80px] border-gray-300 rounded-md focus:border-gray-300 focus:ring-0 text-sm"
            v-model="params.per_page"
          >
            <option value="" selected disabled>Select</option>
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="75">75</option>
            <option value="100">100</option>
          </select>
        </div>
      </div>

      <p
        v-if="feedbackTrashDelete"
        class="text-left text-sm font-bold mb-2 text-warning-600"
      >
        Website Feedbacks in the Trash will be automatically deleted after 60
        days.
        <button
          @click="handlePermanentlyDelete"
          class="text-primary-500 rounded-md px-2 py-1 hover:bg-primary-200 hover:text-primary-600 transition-all hover:animate-bounce"
        >
          Empty the trash now
        </button>
      </p>

      <TableContainer>
        <TableHeader>
          <HeaderTh @click="updateSorting('id')">
            No
            <i
              class="fa-sharp fa-solid fa-arrow-up arrow-icon cursor-pointer"
              :class="{
                'text-blue-600':
                  params.direction === 'asc' && params.sort === 'id',
                'visually-hidden':
                  params.direction !== '' &&
                  params.direction !== 'asc' &&
                  params.sort === 'id',
              }"
            ></i>
            <i
              class="fa-sharp fa-solid fa-arrow-down arrow-icon cursor-pointer"
              :class="{
                'text-blue-600':
                  params.direction === 'desc' && params.sort === 'id',
                'visually-hidden':
                  params.direction !== '' &&
                  params.direction !== 'desc' &&
                  params.sort === 'id',
              }"
            ></i>
          </HeaderTh>
          <HeaderTh @click="updateSorting('email')">
            Email
            <i
              class="fa-sharp fa-solid fa-arrow-up arrow-icon cursor-pointer"
              :class="{
                'text-blue-600':
                  params.direction === 'asc' && params.sort === 'email',
                'visually-hidden':
                  params.direction !== '' &&
                  params.direction !== 'asc' &&
                  params.sort === 'email',
              }"
            ></i>
            <i
              class="fa-sharp fa-solid fa-arrow-down arrow-icon cursor-pointer"
              :class="{
                'text-blue-600':
                  params.direction === 'desc' && params.sort === 'email',
                'visually-hidden':
                  params.direction !== '' &&
                  params.direction !== 'desc' &&
                  params.sort === 'email',
              }"
            ></i>
          </HeaderTh>
          <HeaderTh @click="updateSorting('feedback_text')">
            Feedback
            <i
              class="fa-sharp fa-solid fa-arrow-up arrow-icon cursor-pointer"
              :class="{
                'text-blue-600':
                  params.direction === 'asc' && params.sort === 'feedback_text',
                'visually-hidden':
                  params.direction !== '' &&
                  params.direction !== 'asc' &&
                  params.sort === 'feedback_text',
              }"
            ></i>
            <i
              class="fa-sharp fa-solid fa-arrow-down arrow-icon cursor-pointer"
              :class="{
                'text-blue-600':
                  params.direction === 'desc' &&
                  params.sort === 'feedback_text',
                'visually-hidden':
                  params.direction !== '' &&
                  params.direction !== 'desc' &&
                  params.sort === 'feedback_text',
              }"
            ></i>
          </HeaderTh>
          <HeaderTh @click="updateSorting('rating')">
            Rating
            <i
              class="fa-sharp fa-solid fa-arrow-up arrow-icon cursor-pointer"
              :class="{
                'text-blue-600':
                  params.direction === 'asc' && params.sort === 'rating',
                'visually-hidden':
                  params.direction !== '' &&
                  params.direction !== 'asc' &&
                  params.sort === 'rating',
              }"
            ></i>
            <i
              class="fa-sharp fa-solid fa-arrow-down arrow-icon cursor-pointer"
              :class="{
                'text-blue-600':
                  params.direction === 'desc' && params.sort === 'rating',
                'visually-hidden':
                  params.direction !== '' &&
                  params.direction !== 'desc' &&
                  params.sort === 'rating',
              }"
            ></i>
          </HeaderTh>
          <HeaderTh @click="updateSorting('deleted_at')">
            Deleted At
            <i
              class="fa-sharp fa-solid fa-arrow-up arrow-icon cursor-pointer"
              :class="{
                'text-blue-600':
                  params.direction === 'asc' && params.sort === 'deleted_at',
                'visually-hidden':
                  params.direction !== '' &&
                  params.direction !== 'asc' &&
                  params.sort === 'deleted_at',
              }"
            ></i>
            <i
              class="fa-sharp fa-solid fa-arrow-down arrow-icon cursor-pointer"
              :class="{
                'text-blue-600':
                  params.direction === 'desc' && params.sort === 'deleted_at',
                'visually-hidden':
                  params.direction !== '' &&
                  params.direction !== 'desc' &&
                  params.sort === 'deleted_at',
              }"
            ></i>
          </HeaderTh>
          <HeaderTh v-if="feedbackTrashRestore || feedbackTrashDelete">
            Action
          </HeaderTh>
        </TableHeader>

        <tbody v-if="trashWebsiteFeedbacks.data.length">
          <Tr
            v-for="trashWebsiteFeedback in trashWebsiteFeedbacks.data"
            :key="trashWebsiteFeedback.id"
          >
            <BodyTh>{{ trashWebsiteFeedback.id }}</BodyTh>

            <Td>{{ trashWebsiteFeedback.email }}</Td>
            <Td class="line-clamp-1">{{
              trashWebsiteFeedback.feedback_text
            }}</Td>
            <Td>{{ trashWebsiteFeedback.rating }}</Td>
            <Td>{{ trashWebsiteFeedback.deleted_at }}</Td>
            <Td v-if="feedbackTrashRestore || feedbackTrashDelete">
              <button
                v-if="feedbackTrashRestore"
                @click="handleRestore(trashWebsiteFeedback.id)"
                class="text-sm px-3 py-2 uppercase font-semibold rounded-md bg-blue-600 text-white hover:bg-blue-700 mr-3 my-1"
              >
                <i class="fa-solid fa-recycle"></i>
                Restore
              </button>
              <button
                v-if="feedbackTrashDelete"
                @click="handleDelete(trashWebsiteFeedback.id)"
                class="text-sm px-3 py-2 uppercase font-semibold rounded-md bg-red-600 text-white hover:bg-red-700 mr-3 my-1"
              >
                <i class="fa-solid fa-trash"></i>
                Delete Forever
              </button>
            </Td>
          </Tr>
        </tbody>
      </TableContainer>

      <NotAvaliableData v-if="!trashWebsiteFeedbacks.data.length" />

      <Pagination class="mt-6" :links="trashWebsiteFeedbacks.links" />
    </div>
  </AdminDashboardLayout>
</template>

