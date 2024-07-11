<template>
<!--    {{messages}}-->
    {{user}}

    <div class="chat-message" v-for="message in messages" >
        {{user.id}} asdasd
        <br>
        {{message.user_id}} bbb
        <div class="flex items-end" :class="{'justify-end': message.user_id === user.id}">
            <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2" :class="{'items-end': message.user_id !== user.id, 'items-start': message.user_id === user.id}">
                <div>
                    <span class="px-4 py-2 rounded-lg inline-block"
                          :class="{'rounded-br-none bg-blue-600 text-white': message.user_id === user.id, 'rounded-bl-none bg-gray-300 text-gray-600': message.user_id !== user.id}"
                    >
                        {{ message.message}}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import useChat from "@/Composable/Chat.js";
import { onMounted } from 'vue';
export default {
    name: 'ChatMessages',
    props: {
        user: {
            required: true,
            type: Object,
        }
    },
    setup(props) {
        const {messages, getMessage, userAuth} = useChat()

        onMounted(getMessage);

        Echo.private('chat')
            .listen('MessageSent', (e) => {
                console.log('messagesent', e)
                messages.value.push({
                    message: e.message.message,
                    user_id: e.user.id
                });
            });
        return {
            messages,
        }
    },
};

</script>

<style scoped>

</style>
