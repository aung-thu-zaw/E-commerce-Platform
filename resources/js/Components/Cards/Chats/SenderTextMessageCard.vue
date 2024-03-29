<script setup>
import DropdownToolForChatMessage from "@/Components/Dropdowns/Chats/DropdownToolsForChatMessage.vue";
import { computed, ref } from "vue";

const props = defineProps({
  message: Object,
  msgScroll: Object,
});

const emits = defineEmits(["replyMessage"]);

const replyMessage = (message) => {
  emits("replyMessage", message);
};

const scrollToOriginalMessage = (messageId) => {
  const originalMessageElement = props.msgScroll.querySelector(
    `#message-${messageId}`
  );

  if (originalMessageElement) {
    originalMessageElement.style.backgroundColor = "#f3f4f6ff";

    originalMessageElement.scrollIntoView({ behavior: "smooth" });

    setTimeout(() => {
      originalMessageElement.style.backgroundColor = "transparent";
    }, 3000);
  }
};
</script>


<template>
  <div
    v-if="
      $page.url.startsWith('/admin/live-chats')
        ? !message.is_deleted_by_agent
        : !message.is_deleted_by_user
    "
    class="flex items-end justify-end"
  >
    <div class="flex items-center justify-end">
      <div class="pl-28">
        <div class="flex items-center justify-end">
          <DropdownToolForChatMessage
            :message="message"
            @replyMessage="replyMessage"
          />

          <div
            v-if="message.reply_to_message_id"
            @click="scrollToOriginalMessage(message.reply_to_message_id)"
            class="p-3 bg-gray-50 border-2 border-slate-300 rounded-xl rounded-br-none shadow-md w-auto max-w-[500px] text-sm flex flex-col cursor-pointer"
          >
            <div>
              <div class="flex items-center text-xs text-slate-500">
                <i class="fa-solid fa-reply mr-2"></i>

                <p class="line-clamp-1">
                  {{ message?.reply_to_message?.message }}
                </p>
              </div>
            </div>
            <p class="self-end">
              {{ message.message }}
            </p>
          </div>

          <div
            v-else
            class="p-3 bg-gray-50 border-2 border-slate-300 rounded-xl rounded-br-none shadow-md w-auto max-w-[500px] text-sm flex flex-col"
          >
            <p class="self-end">
              {{ message.message }}
            </p>
          </div>
        </div>
        <div
          class="mt-1 text-[.6rem] text-slate-600 flex items-center justify-end space-x-4"
        >
          <div class="flex items-center justify-end space-x-2 mr-2">
            <span class=""> {{ message.updated_at }} </span>
            <span
              v-if="message.is_read_by_user || message.is_read_by_agent"
              class="text-[.6rem] text-slate-600"
            >
              <i
                v-if="message.is_read_by_user"
                class="fa-solid fa-check text-green-500"
              ></i>
              <i
                v-if="message.is_read_by_agent"
                class="fa-solid fa-check text-green-500"
              ></i>
            </span>
          </div>
        </div>
      </div>
    </div>
    <img
      :src="
        $page.url.startsWith('/admin/live-chats')
          ? message?.agent?.avatar
          : message?.user?.avatar
      "
      class="w-8 h-8 object-cover rounded-full ring-2 ring-slate-300 ml-3"
    />
  </div>
</template>

