<script setup>
import { Link, router } from "@inertiajs/vue3";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import { computed } from "vue";

dayjs.extend(relativeTime);

const props = defineProps({
  notification: Object,
});

const goToDetailPage = () => {
  router.get(route("user.shop.followed"));
};

const handleNotificationReadAt = () => {
  router.patch(
    route("seller.notifications.read", props.notification.id),
    {
      notifiable_id: props.notification.notifiable_id,
    },
    {
      onSuccess: () => {
        goToDetailPage();
      },
    }
  );
};
</script>

<template>
  <div
    @click="handleNotificationReadAt"
    v-if="notification.type === 'App\\Notifications\\FollowedShopNotification'"
    href="#"
    class="flex px-4 py-3 hover:bg-gray-100 cursor-pointer"
    :class="{ 'bg-gray-50': notification.read_at }"
  >
    <div
      class="flex-shrink-0 bg-teal-300 text-teal-700 ring-2 ring-teal-500 w-10 h-10 rounded-md flex items-center justify-center p-3 font-bold"
    >
      <i class="fa-solid fa-user-plus"></i>
    </div>
    <div class="w-full pl-3">
      <div
        class="text-sm mb-1.5"
        :class="{
          'text-gray-600': !notification.read_at,
          'text-gray-500': notification.read_at,
        }"
      >
        {{ notification.data.name + " " + __(notification.data.message) }}
      </div>
      <div
        class="text-xs font-bold"
        :class="{
          'text-teal-500': !notification.read_at,
          'text-gray-500': notification.read_at,
        }"
      >
        <i
          v-if="!notification.read_at"
          class="fa-solid fa-circle animate-pulse text-[.6rem]"
        ></i>
        {{ dayjs(notification.created_at).fromNow() }}
      </div>
    </div>
  </div>
</template>
