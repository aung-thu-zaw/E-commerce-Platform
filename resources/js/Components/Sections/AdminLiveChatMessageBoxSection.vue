<script setup>
import LastChatDiscussionInformationCard from "@/Components/Cards/Chats/LastChatDiscussionInformationCard.vue";
import AdminDashboardLiveChatBoxHeader from "@/Components/Headers/AdminDashboardLiveChatBoxHeader.vue";
import AgentLiveChatMessageForm from "@/Components/Forms/Chats/AgentLiveChatMessageForm.vue";
import SenderTextMessageCard from "@/Components/Cards/Chats/SenderTextMessageCard.vue";
import SenderFileMessageCard from "@/Components/Cards/Chats/SenderFileMessageCard.vue";
import ReceiverFileMessageCard from "@/Components/Cards/Chats/ReceiverFileMessageCard.vue";
import RecevierTextMessageCard from "@/Components/Cards/Chats/RecevierTextMessageCard.vue";
import { onMounted, onUpdated, ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
  selectedLiveChat: Object,
});

const msgScroll = ref(null);
const messageToReply = ref(null);
const isNewMessageSent = ref(false);
// Auto Scroll To Bottom
const scrollToBottom = () => {
  if (isNewMessageSent.value) {
    msgScroll.value.scrollTop = msgScroll.value.scrollHeight;
    isNewMessageSent.value = false;
  }
};
onUpdated(() => {
  scrollToBottom();
});

const setMessageToReply = (message) => {
  messageToReply.value = message;
};

const cancelReplyMessage = () => {
  messageToReply.value = null;
};

onMounted(() => {
  Echo.private(`live-chat.message`).listen("LiveChatMessageSent", (data) => {
    if (props.selectedLiveChat.id === data.liveChatMessage.live_chat_id) {
      props.selectedLiveChat.live_chat_messages.push(data.liveChatMessage);
      isNewMessageSent.value = true;
    }
  });

  Echo.private(`live-chat.message`).listen("LiveChatMessageDeleted", (data) => {
    if (props.selectedLiveChat.id === data.liveChatMessage.live_chat_id) {
      console.log(data.liveChatMessage);
      const index = props.selectedLiveChat.live_chat_messages.findIndex(
        (message) => message.id === data.liveChatMessage.id
      );

      if (
        (index !== -1 && data.liveChatMessage.is_deleted_by_agent) ||
        (index !== -1 &&
          data.liveChatMessage.is_deleted_by_user === 0 &&
          data.liveChatMessage.is_deleted_by_agent === 0)
      ) {
        props.selectedLiveChat.live_chat_messages.splice(index, 1);
      }
    }
  });
});
</script>

<template>
  <div v-if="selectedLiveChat" class="w-full h-full overflow-hidden">
    <!-- Header -->
    <AdminDashboardLiveChatBoxHeader :liveChat="selectedLiveChat" />

    <!-- Search Form And Result  -->
    <div
      v-if="isMessageSearchFormOpened"
      class="fixed w-[1295px] top-[144px] h-auto border-t border-b bg-white py-2 px-10"
    >
      <!-- Search Message Form -->
      <div class="flex items-center justify-between space-x-10">
        <form
          class="flex items-center rounded-full px-5 bg-slate-100 py-1 border w-full"
        >
          <input
            type="text"
            class="w-full border-none focus:ring-0 text-xs bg-transparent"
            :placeholder="__('SEARCH_MESSAGE')"
          />
          <i
            class="fa-solid fa-xmark ml-1 text-sm text-slate-500 hover:text-red-600"
          ></i>
        </form>
        <div class="flex items-center space-x-5 w-auto">
          <div
            @click="isMessageSearchFormOpened = false"
            class="w-5 h-5 p-1 rounded-full flex items-center justify-center bg-gray-300 cursor-pointer hover:bg-gray-200"
          >
            <i class="fa-solid fa-xmark text-xs"></i>
          </div>
          <div class="text-slate-600 hover:text-slate-500 cursor-pointer">
            <i class="fa-solid fa-calendar"></i>
          </div>
        </div>
      </div>

      <!-- Result -->
      <!-- <div class="mt-5">
              <ul class="">
                <li
                  class="w-full border-t py-1 hover:bg-gray-100 px-5 rounded-sm cursor-pointer"
                >
                  <div class="flex items-center">
                    <img
                      src="https://img.freepik.com/free-psd/3d-illustration-person-with-glasses_23-2149436189.jpg"
                      class="w-8 h-8 rounded-full object-cover mr-3"
                    />
                    <div class="w-full">
                      <div
                        class="text-xs flex items-center justify-between w-full"
                      >
                        <h6 class="font-semibold">Aung Thu Zaw</h6>
                        <span class="text-slate-700 font-medium"
                          >3-September-2023</span
                        >
                      </div>
                      <span class="text-xs text-slate-600">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Nobis nihil dolorem sit quia reiciendis earum ea magnam,
                        modi perspiciatis rerum amet facere vel dicta cupiditate
                        aliquid iste fuga quod harum.
                      </span>
                    </div>
                  </div>
                </li>
              </ul>
            </div> -->
    </div>

    <!-- Message -->
    <div class="h-[810px] bg-white flex flex-col justify-end">
      <div class="overflow-auto scrollbar h-auto" ref="msgScroll">
        <LastChatDiscussionInformationCard
          v-if="!selectedLiveChat.is_active && selectedLiveChat.ended_at"
          :liveChat="selectedLiveChat"
        />
        <div
          v-for="message in selectedLiveChat?.live_chat_messages"
          :key="message.id"
          :id="'message-' + message.id"
          class="px-5 py-2"
        >
          <!-- Left Side For Recevier -->
          <div v-if="message.user_id && !message.agent_id && message.message">
            <RecevierTextMessageCard
              :message="message"
              :msgScroll="msgScroll"
              @editMessage="setMessageToEdit"
              @replyMessage="setMessageToReply"
            />
          </div>
          <div
            v-if="
              message.user_id &&
              !message.agent_id &&
              message.chat_file_attachments.length
            "
          >
            <ReceiverFileMessageCard :message="message" />
          </div>

          <!-- Right Side For Sender  -->
          <div v-if="message.agent_id && !message.user_id && message.message">
            <SenderTextMessageCard
              :message="message"
              :msgScroll="msgScroll"
              @editMessage="setMessageToEdit"
              @replyMessage="setMessageToReply"
            />
          </div>
          <div
            v-if="
              !message.user_id &&
              message.agent_id &&
              message.chat_file_attachments.length
            "
          >
            <SenderFileMessageCard :message="message" />
          </div>
        </div>

        <p
          v-if="!selectedLiveChat.is_active && selectedLiveChat.ended_at"
          class="text-sm font-bold text-gray-500 w-full text-center mt-5"
        >
          {{ __("THE_CHAT_HAS_ENDED") }}
        </p>
      </div>

      <AgentLiveChatMessageForm
        :liveChat="selectedLiveChat"
        :messageToEdit="messageToEdit"
        @cancelEditMessage="cancelEditMessage"
        :messageToReply="messageToReply"
        @cancelReplyMessage="cancelReplyMessage"
      />
    </div>
  </div>
</template>
