<script setup>
import Breadcrumb from "@/Components/Breadcrumbs/CancelOrderManage/Breadcrumb.vue";
import NotAvaliableData from "@/Components/Table/NotAvaliableData.vue";
import ApprovedStatus from "@/Components/Table/ApprovedStatus.vue";
import Tr from "@/Components/Table/Tr.vue";
import Td from "@/Components/Table/Td.vue";
import HeaderTh from "@/Components/Table/HeaderTh.vue";
import BodyTh from "@/Components/Table/BodyTh.vue";
import TableHeader from "@/Components/Table/TableHeader.vue";
import TableContainer from "@/Components/Table/TableContainer.vue";
import Pagination from "@/Components/Pagination.vue";
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import { Link, Head, router } from "@inertiajs/vue3";
import { reactive, watch } from "vue";

const props = defineProps({
  approvedCancelOrders: Object,
});

const params = reactive({
  search: null,
  page: props.approvedCancelOrders.current_page
    ? props.approvedCancelOrders.current_page
    : 1,
  per_page: props.approvedCancelOrders.per_page
    ? props.approvedCancelOrders.per_page
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
      route("admin.cancel-orders.approved.index"),
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
      route("admin.cancel-orders.approved.index"),
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
    route("admin.cancel-orders.approved.index"),
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
</script>


<template>
  <AdminDashboardLayout>
    <Head title="Approved Cancel Orders" />

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
                >Approved Cancel</span
              >
            </div>
          </li>
        </Breadcrumb>
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
          <HeaderTh @click="updateSorting('invoice_no')">
            Invoice
            <i
              class="fa-sharp fa-solid fa-arrow-up arrow-icon cursor-pointer"
              :class="{
                'text-blue-600':
                  params.direction === 'asc' && params.sort === 'invoice_no',
                'visually-hidden':
                  params.direction !== '' &&
                  params.direction !== 'asc' &&
                  params.sort === 'invoice_no',
              }"
            ></i>
            <i
              class="fa-sharp fa-solid fa-arrow-down arrow-icon cursor-pointer"
              :class="{
                'text-blue-600':
                  params.direction === 'desc' && params.sort === 'invoice_no',
                'visually-hidden':
                  params.direction !== '' &&
                  params.direction !== 'desc' &&
                  params.sort === 'invoice_no',
              }"
            ></i>
          </HeaderTh>
          <HeaderTh @click="updateSorting('payment_type')">
            Payment
            <i
              class="fa-sharp fa-solid fa-arrow-up arrow-icon cursor-pointer"
              :class="{
                'text-blue-600':
                  params.direction === 'asc' && params.sort === 'payment_type',
                'visually-hidden':
                  params.direction !== '' &&
                  params.direction !== 'asc' &&
                  params.sort === 'payment_type',
              }"
            ></i>
            <i
              class="fa-sharp fa-solid fa-arrow-down arrow-icon cursor-pointer"
              :class="{
                'text-blue-600':
                  params.direction === 'desc' && params.sort === 'payment_type',
                'visually-hidden':
                  params.direction !== '' &&
                  params.direction !== 'desc' &&
                  params.sort === 'payment_type',
              }"
            ></i>
          </HeaderTh>
          <HeaderTh @click="updateSorting('total_amount')">
            Amount
            <i
              class="fa-sharp fa-solid fa-arrow-up arrow-icon cursor-pointer"
              :class="{
                'text-blue-600':
                  params.direction === 'asc' && params.sort === 'total_amount',
                'visually-hidden':
                  params.direction !== '' &&
                  params.direction !== 'asc' &&
                  params.sort === 'total_amount',
              }"
            ></i>
            <i
              class="fa-sharp fa-solid fa-arrow-down arrow-icon cursor-pointer"
              :class="{
                'text-blue-600':
                  params.direction === 'desc' && params.sort === 'total_amount',
                'visually-hidden':
                  params.direction !== '' &&
                  params.direction !== 'desc' &&
                  params.sort === 'total_amount',
              }"
            ></i>
          </HeaderTh>
          <HeaderTh> Cancel Status </HeaderTh>
          <HeaderTh @click="updateSorting('order_date')">
            Cancel Date
            <i
              class="fa-sharp fa-solid fa-arrow-up arrow-icon cursor-pointer"
              :class="{
                'text-blue-600':
                  params.direction === 'asc' && params.sort === 'cancel_date',
                'visually-hidden':
                  params.direction !== '' &&
                  params.direction !== 'asc' &&
                  params.sort === 'cancel_date',
              }"
            ></i>
            <i
              class="fa-sharp fa-solid fa-arrow-down arrow-icon cursor-pointer"
              :class="{
                'text-blue-600':
                  params.direction === 'desc' && params.sort === 'cancel_date',
                'visually-hidden':
                  params.direction !== '' &&
                  params.direction !== 'desc' &&
                  params.sort === 'cancel_date',
              }"
            ></i>
          </HeaderTh>
          <HeaderTh> Action </HeaderTh>
        </TableHeader>

        <tbody v-if="approvedCancelOrders.data.length">
          <Tr
            v-for="approvedCancelOrder in approvedCancelOrders.data"
            :key="approvedCancelOrder.id"
          >
            <BodyTh>{{ approvedCancelOrder.id }}</BodyTh>
            <Td>{{ approvedCancelOrder.invoice_no }}</Td>
            <Td class="capitalize">{{ approvedCancelOrder.payment_type }}</Td>
            <Td>$ {{ approvedCancelOrder.total_amount }}</Td>
            <Td>
              <ApprovedStatus>
                {{ approvedCancelOrder.cancel_status }}
              </ApprovedStatus>
            </Td>
            <Td>{{ approvedCancelOrder.return_date }}</Td>

            <Td>
              <Link
                as="button"
                :href="
                  route(
                    'admin.return-orders.approved.show',
                    approvedCancelOrder.id
                  )
                "
                :data="{
                  page: props.approvedCancelOrders.current_page,
                  per_page: params.per_page,
                }"
                class="text-sm px-3 py-2 uppercase font-semibold rounded-md bg-sky-600 text-white hover:bg-sky-700 my-1"
              >
                <i class="fa-solid fa-eye"></i>
                Details
              </Link>
            </Td>
          </Tr>
        </tbody>
      </TableContainer>

      <NotAvaliableData v-if="!approvedCancelOrders.data.length" />

      <Pagination class="mt-6" :links="approvedCancelOrders.links" />
    </div>
  </AdminDashboardLayout>
</template>
